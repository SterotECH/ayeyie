<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Orders;

use App\Enums\AuditAction;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Pickup;
use App\Services\CartService;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

final class Create extends Component
{

    public array $cartItems = [];

    public float $subtotal = 0;

    public float $tax = 0;

    public float $total = 0;

    public string $paymentMethod = 'cash';

    public bool $agreeToTerms = false;

    // Customer information (pre-filled for authenticated users)
    public string $customerName = '';

    public string $customerEmail = '';

    public string $customerPhone = '';

    public bool $processingPayment = false;

    // Pickup date (customer preference)
    public string $preferredPickupDate = '';

    // Validation rules
    protected function rules(): array
    {
        return [
            'paymentMethod' => 'required|string|in:cash',
            'agreeToTerms' => 'required|accepted',
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'customerPhone' => 'nullable|string|max:20',
            'preferredPickupDate' => 'required|date|after:today',
        ];
    }

    protected function messages(): array
    {
        return [
            'agreeToTerms.accepted' => 'You must agree to the terms and conditions to proceed.',
            'paymentMethod.required' => 'Please select a payment method.',
            'customerName.required' => 'Please enter your full name.',
            'customerEmail.required' => 'Please enter your email address.',
            'customerEmail.email' => 'Please enter a valid email address.',
            'preferredPickupDate.required' => 'Please select your preferred pickup date.',
            'preferredPickupDate.after' => 'Pickup date must be tomorrow or later.',
        ];
    }

    public function mount(): void
    {
        // Ensure customer role access
        if (Auth::user()?->role !== 'customer') {
            abort(403, 'Access denied');
        }

        // Load cart items and validate them
        $this->loadCartItems();

        // Pre-fill customer information
        if (Auth::check()) {
            $user = Auth::user();
            $this->customerName = $user->name;
            $this->customerEmail = $user->email;
            $this->customerPhone = $user->phone ?? '';
        }

        // Redirect if cart is empty
        if (empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty. Please add items before proceeding to checkout.');

            $this->redirect(route('welcome.products.index'), navigate: true);
        }
    }

    public function hydrate(): void
    {
        // Recalculate totals after hydration to ensure consistency
        $this->calculateTotals();
    }

    public function loadCartItems(): void
    {
        $validation = app(CartService::class)->validateCartItems();
        $this->cartItems = $validation['valid_items'];

        if (! empty($validation['invalid_items'])) {
            foreach ($validation['invalid_items'] as $message) {
                session()->flash('warning', $message);
            }
        }

        $this->calculateTotals();

        // Force refresh of component state
        $this->dispatch('cart-updated');
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        app(CartService::class)->updateQuantity($productId, $quantity);
        $this->loadCartItems();

        // Force re-render to update UI
        $this->skipRender = false;
    }

    public function removeItem(int $productId): void
    {
        app(CartService::class)->removeFromCart($productId);
        $this->loadCartItems();

        // Redirect if cart becomes empty
        if (empty($this->cartItems)) {
            session()->flash('info', 'Cart is now empty.');

            $this->redirect(route('welcome.products.index'), navigate: true);
        }
    }

    public function clearCart(): void
    {
        app(CartService::class)->clearCart();
        session()->flash('info', 'Cart cleared successfully.');

        $this->redirect(route('welcome.products.index'), navigate: true);
    }

    public function continueShopping(): void
    {
        $this->redirect(route('welcome.products.index'), navigate: true);
    }

    /**
     * @throws \Throwable
     */
    public function proceedToPayment()
    {
        $this->validate();

        if (empty($this->cartItems)) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        $this->processingPayment = true;

        try {
            DB::beginTransaction();

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'customer_user_id' => Auth::id(),
                'total_amount' => $this->total,
                'payment_status' => 'pending',
                'payment_method' => $this->paymentMethod,
                'transaction_date' => CarbonImmutable::now(),
                'is_synced' => false,
            ]);

            // Create transaction items
            foreach ($this->cartItems as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                
                TransactionItem::create([
                    'transaction_id' => $transaction->transaction_id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product?->decrement('stock_quantity', $item['quantity']);
            }

            // Create receipt
            $receiptCode = $this->generateReceiptCode();
            $receipt = Receipt::create([
                'transaction_id' => $transaction->transaction_id,
                'receipt_code' => $receiptCode,
                'issued_at' => CarbonImmutable::now(),
                'qr_code' => $this->generateQRCode($receiptCode, $transaction->transaction_id),
                'is_synced' => false,
            ]);

            // Create pickup record (pending until customer picks up)
            $pickup = Pickup::create([
                'receipt_id' => $receipt->receipt_id,
                'user_id' => null, // Will be set when staff processes pickup
                'pickup_status' => 'pending',
                'pickup_date' => CarbonImmutable::parse($this->preferredPickupDate)->startOfDay(), // Customer's preferred pickup date
                'is_synced' => false,
            ]);

            // Log pickup creation
            app(\App\Services\AuditLogService::class)->log(
                action: AuditAction::PICKUP_CREATED,
                entity: $pickup,
                user: Auth::user(),
                details: [
                    'transaction_id' => $transaction->transaction_id,
                    'receipt_code' => $receiptCode,
                    'pickup_status' => 'pending'
                ]
            );

            // Log the transaction creation
            app(\App\Services\AuditLogService::class)->log(
                action: AuditAction::TRANSACTION_CREATED,
                entity: $transaction,
                user: Auth::user(),
                details: [
                    'payment_method' => $this->paymentMethod,
                    'item_count' => count($this->cartItems),
                    'total_amount' => $this->total,
                ],
            );

            DB::commit();

            $this->pendingTransactionId = $transaction->transaction_id;

            // Handle cash payment with pickup
            $this->handleCashPayment($transaction);
            return;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->processingPayment = false;

            session()->flash('error', 'Failed to create order. Please try again.');
            logger()->error('Order creation failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'cart_items' => $this->cartItems,
            ]);
        }
    }

    private function handleCashPayment(Transaction $transaction): void
    {
        try {
            // Set payment status for cash payment
            $paymentStatus = 'pending'; // Cash payment remains pending until pickup
            
            // Update transaction status if needed
            if ($transaction->payment_status !== $paymentStatus) {
                $transaction->update(['payment_status' => $paymentStatus]);
            }
            
            // Log the order creation
            app(\App\Services\AuditLogService::class)->log(
                action: \App\Enums\AuditAction::TRANSACTION_CREATED,
                entity: $transaction,
                user: Auth::user(),
                details: [
                    'payment_method' => $this->paymentMethod,
                    'payment_status' => $paymentStatus,
                    'item_count' => count($this->cartItems),
                    'total_amount' => $this->total,
                    'pickup_default' => true
                ]
            );
            
            // Clear cart
            app(CartService::class)->clearCart();
            
            // Set success message for cash payment with pickup
            $message = 'Order placed successfully! Please visit our store for pickup and pay on pickup. Delivery is available for an additional fee.';
            
            session()->flash('success', $message);
            
            $this->redirect(route('customers.orders.show', $transaction), navigate: true);
            
        } catch (\Exception $e) {
            $this->processingPayment = false;
            session()->flash('error', 'Failed to process order. Please try again.');
            logger()->error('Cash payment processing failed', [
                'error' => $e->getMessage(),
                'payment_method' => $this->paymentMethod,
                'transaction_id' => $transaction->transaction_id
            ]);
        }
    }


    public function render()
    {
        return view('livewire.customer.orders.create', [
            'stats' => $this->getStats(),
        ]);
    }


    private function calculateTotals()
    {
        $this->subtotal = 0;

        foreach ($this->cartItems as $item) {
            // Ensure we're working with numeric values
            $price = (float) $item['price'];
            $quantity = (int) $item['quantity'];
            $itemTotal = $price * $quantity;

            $this->subtotal += $itemTotal;
        }

        // Calculate tax (0% for now, but structure is ready)
        $this->tax = 0; // $this->subtotal * 0.125; // 12.5% VAT
        $this->total = $this->subtotal + $this->tax;
    }

    private function generateReceiptCode(): string
    {
        $prefix = 'AYE';
        $timestamp = now()->format('ymd');
        $random = str_pad((string) mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

        return $prefix . $timestamp . $random;
    }

    private function generateQRCode(string $receiptCode, int $transactionId): string
    {
        // Generate QR code with order verification URL
        $verificationUrl = route('staff.orders.verify', [
            'receipt_code' => $receiptCode,
            'transaction_id' => $transactionId
        ]);
        
        // Generate QR code as base64 data URL
        $qrCode = QrCode::format('svg')
            ->size(200)
            ->margin(2)
            ->generate($verificationUrl);
            
        return 'data:image/svg+xml;base64,' . base64_encode((string) $qrCode);
    }

    private function getStats(): array
    {
        return [
            'cart_items' => count($this->cartItems),
            'subtotal' => $this->subtotal,
            'tax' => $this->tax,
            'total' => $this->total,
        ];
    }
}
