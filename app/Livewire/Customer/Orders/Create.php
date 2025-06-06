<?php

namespace App\Livewire\Customer\Orders;

use App\Models\Pickup;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Session;
use Livewire\Component;
use Str;

class Create extends Component
{
    #[Session('cartItems.auth::id()')]
    public array $cartItems = [];
    public float $totalAmount = 0;
    public int $amount = 0;
    public bool $showCart = false;
    public string  $paymentMethod = 'mobile_money';
    public bool $checkoutMode = false;
    public string  $searchQuery = '';
    public string $phoneNumber;
    public null|string $reference;
    public null|string $transactionId;
    private $products = [];

    protected $listeners = ['productAdded' => 'updateCart'];

    protected $queryString = [
        'searchQuery' => ['except' => '', 'as' => 'q'],
    ];

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product || $product->stock_quantity <= 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Product is out of stock!'
            ]);
            return;
        }

        // Check if the product is already in the cart
        $existingItem = collect($this->cartItems)->firstWhere('product_id', $productId);

        if ($existingItem) {
            // Increase quantity if already in cart
            if ($existingItem['quantity'] < $product->stock_quantity) {
                $this->cartItems = collect($this->cartItems)->map(function ($item) use ($productId, $product) {
                    if ($item['product_id'] == $productId) {
                        $item['quantity']++;
                        $item['subtotal'] = $item['quantity'] * $item['unit_price'];
                    }
                    return $item;
                })->toArray();
            } else {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Cannot add more of this item, stock limit reached!'
                ]);
                return;
            }
        } else {
            // Add new item to cart
            $this->cartItems[] = [
                'product_id' => $product->product_id,
                'name' => $product->name,
                'quantity' => 1,
                'unit_price' => $product->price,
                'subtotal' => $product->price,
                'max_quantity' => $product->stock_quantity
            ];
        }

        $this->calculateTotal();
        $this->showCart = true;

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Product added to cart!'
        ]);
    }

    public function updateQuantity($index, $change)
    {
        $item = $this->cartItems[$index];
        $newQuantity = $item['quantity'] + $change;

        if ($newQuantity > 0 && $newQuantity <= $item['max_quantity']) {
            $this->cartItems[$index]['quantity'] = $newQuantity;
            $this->cartItems[$index]['subtotal'] = $newQuantity * $item['unit_price'];
            $this->calculateTotal();
        }
    }

    public function removeItem($index)
    {
        unset($this->cartItems[$index]);
        $this->cartItems = array_values($this->cartItems);
        $this->calculateTotal();

        if (count($this->cartItems) == 0) {
            $this->showCart = false;
        }
    }

    public function calculateTotal()
    {
        $this->totalAmount = collect($this->cartItems)->sum('subtotal');
        $this->amount = $this->totalAmount * 100;
    }

    public function toggleCart()
    {
        $this->showCart = !$this->showCart;
    }

    public function startCheckout()
    {
        if (count($this->cartItems) == 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Your cart is empty!'
            ]);
            return;
        }

        $this->checkoutMode = true;
    }

    public function cancelCheckout()
    {
        $this->checkoutMode = false;
    }

    public function processTransaction()
    {
        // Validate cart has items
        if (count($this->cartItems) == 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Your cart is empty!'
            ]);
            return;
        }

        try {
            \DB::beginTransaction();

            // Create the transaction
            $transaction = Transaction::create([
                'user_id' => null,
                'customer_user_id' => Auth::user()->role === 'customer' ? Auth::id() : null,
                'total_amount' => $this->totalAmount,
                'payment_status' => 'pending',
                'payment_method' => $this->paymentMethod,
                'transaction_date' => now(),
                'is_synced' => false
            ]);

            $this->transactionId = $transaction->transaction_id;

            // Create transaction items
            foreach ($this->cartItems as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->transaction_id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal']
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->stock_quantity -= $item['quantity'];
                $product->save();
            }

            // Generate receipt
            $receiptCode = 'RCP-' . strtoupper(Str::random(8));
            $qrCode = 'https://yourapp.com/verify/' . $receiptCode; // This would be generated properly

            $receipt = Receipt::create([
                'transaction_id' => $transaction->transaction_id,
                'receipt_code' => $receiptCode,
                'qr_code' => $qrCode,
                'issued_at' => now(),
                'is_synced' => false
            ]);

            $pickup = Pickup::create([
                'receipt_id' => $receipt->receipt_id,
                'pickup_status' => 'pending',
                'pickup_date' => null,
                'is_synced' => false
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.paystack.payment_url') . '/transaction/initialize', [
                'amount' => $this->totalAmount * 100,
                'email' => Auth::user()->email ?? 'agyeisterogh@gmail.com',
                'currency' => 'GHS',
                'reference' => 'TXN-' . $transaction->transaction_id,
                'channels' => ['mobile_money'],
                'mobile_money' => ['phone' => $this->phoneNumber],
                'metadata' => ['transaction_id' => $transaction->transaction_id],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $this->reference = $data['data']['reference'];
                $this->dispatch('payment-initialized', ['url' => $data['data']['authorization_url']]);
            } else {
                throw new \Exception('Payment initialization failed: ' . $response->json('message', 'Unknown error'));
            }

            // Commit transaction
            \DB::commit();

            // Clear cart
            $this->cartItems = [];
            $this->totalAmount = 0;
            $this->checkoutMode = false;
            $this->showCart = false;

            // Refresh products list to show updated stock quantities
            $this->products = Product::where('stock_quantity', '>', 0)->get();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Transaction completed successfully! Receipt code: ' . $receiptCode
            ]);

            // Redirect to receipt view or stay on page
            // return redirect()->route('receipt.view', ['code' => $receiptCode]);
        } catch (\Exception $e) {
            \DB::rollBack();

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Transaction failed: ' . $e->getMessage()
            ]);
            dd($e->getMessage());
        }
    }

    public function simulatePayment()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.paystack.secret_key'),
            ])->get(config('services.paystack.payment_url') . "/transaction/verify/{$this->reference}");

            if ($response->successful() && $response->json('data.status') === 'success') {
                $data = $response->json('data');
                $transaction = Transaction::find($this->transactionId);

                if ($transaction) {
                    \DB::beginTransaction();

                    $transaction->update([
                        'payment_status' => 'completed',
                        'payment_method' => $data['mobile_money'],
                        'is_synced' => true,
                    ]);

                    foreach ($this->cartItems as $item) {
                        TransactionItem::create([
                            'transaction_id' => $transaction->transaction_id,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'unit_price' => $item['unit_price'],
                            'subtotal' => $item['subtotal'],
                        ]);
                        Product::find($item['product_id'])->decrement('stock_quantity', $item['quantity']);
                    }

                    $receiptCode = 'RCP-' . strtoupper(Str::random(8));
                    $qrCode = 'https://yourapp.com/verify/' . $receiptCode;

                    $receipt = Receipt::create([
                        'transaction_id' => $transaction->transaction_id,
                        'receipt_code' => $receiptCode,
                        'qr_code' => $qrCode,
                        'issued_at' => now(),
                        'is_synced' => true,
                    ]);

                    Pickup::create([
                        'receipt_id' => $receipt->receipt_id,
                        'pickup_status' => 'pending',
                        'pickup_date' => null,
                        'is_synced' => true,
                    ]);

                    \DB::commit();

                    $this->cartItems = [];
                    $this->totalAmount = 0;
                    $this->checkoutMode = false;
                    $this->showCart = false;
                    $this->reference = null;
                    $this->transactionId = null;

                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => 'Payment completed successfully! Receipt code: ' . $receiptCode,
                    ]);
                }
            } else {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Payment verification failed or pending.']);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Simulation failed: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $products = Product::where('stock_quantity', '>', 0)->get();
        if (empty($this->searchQuery)) {
            $products = Product::where('stock_quantity', '>', 0)->get();
        } else {
            $products = Product::query()->whereLike('name', '%' . $this->searchQuery . '%')
                ->where('stock_quantity', '>', 0)
                ->get();
        }
        return view('livewire.customer.orders.create', [
            'products' => $products,
        ]);
    }
}
