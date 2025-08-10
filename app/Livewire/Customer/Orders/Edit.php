<?php

namespace App\Livewire\Customer\Orders;

use App\Models\Transaction;
use App\Models\Product;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Flux\Flux;

class Edit extends Component
{
    public Transaction $transaction;
    public string $customerNotes = '';
    public string $paymentMethod = '';
    public string $paymentStatus = '';
    public array $transactionItems = [];
    
    
    public function mount(Transaction $transaction)
    {
        // Ensure customer role access and ownership
        if (Auth::user()?->role !== 'customer' || $transaction->customer_user_id !== Auth::id()) {
            abort(403, 'Access denied');
        }
        
        // Only allow editing of pending orders
        if ($transaction->payment_status !== 'pending') {
            session()->flash('error', 'Only pending orders can be edited.');
            return $this->redirect(route('customers.orders.show', $transaction), navigate: true);
        }
        
        $this->transaction = $transaction->load(['items.product', 'customer']);
        $this->paymentMethod = $transaction->payment_method;
        $this->paymentStatus = $transaction->payment_status;
        
        // Load transaction items with product details
        $this->transactionItems = $this->transaction->items->map(function ($item) {
            return [
                'item_id' => $item->item_id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'stock_quantity' => $item->product->stock_quantity,
                'original_quantity' => $item->quantity,
            ];
        })->toArray();
    }
    
    public function updateQuantity(int $itemIndex, int $newQuantity)
    {
        if (!isset($this->transactionItems[$itemIndex])) {
            return;
        }
        
        $item = &$this->transactionItems[$itemIndex];
        
        if ($newQuantity < 1) {
            Flux::toast(
                text: 'Quantity cannot be less than 1',
                variant: 'warning'
            );
            return;
        }
        
        if ($newQuantity > ($item['stock_quantity'] + $item['original_quantity'])) {
            Flux::toast(
                text: 'Not enough stock available',
                variant: 'warning'
            );
            return;
        }
        
        $item['quantity'] = $newQuantity;
    }
    
    public function removeItem(int $itemIndex)
    {
        if (count($this->transactionItems) <= 1) {
            Flux::toast(
                text: 'Cannot remove the last item. Cancel the order instead.',
                variant: 'warning'
            );
            return;
        }
        
        unset($this->transactionItems[$itemIndex]);
        $this->transactionItems = array_values($this->transactionItems);
    }
    
    public function updatePaymentMethod()
    {
        $this->validate([
            'paymentMethod' => 'required|string|in:paystack,cash,bank_transfer'
        ]);
        
        // Update the transaction
        $this->transaction->update([
            'payment_method' => $this->paymentMethod
        ]);
        
        Flux::toast(
            text: 'Payment method updated successfully',
            variant: 'success'
        );
    }
    
    public function saveChanges()
    {
        $this->validate([
            'paymentMethod' => 'required|string|in:paystack,cash,bank_transfer'
        ]);
        
        if (empty($this->transactionItems)) {
            Flux::toast(
                text: 'Order must contain at least one item',
                variant: 'danger'
            );
            return;
        }
        
        try {
            \DB::beginTransaction();
            
            // Calculate new total
            $newTotal = 0;
            
            foreach ($this->transactionItems as $item) {
                $newTotal += $item['unit_price'] * $item['quantity'];
                
                // Update the transaction item
                $transactionItem = $this->transaction->items()->where('item_id', $item['item_id'])->first();
                if ($transactionItem) {
                    // Restore original stock
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->increment('stock_quantity', $transactionItem->quantity);
                        
                        // Check new stock availability
                        if ($product->stock_quantity < $item['quantity']) {
                            throw new \Exception("Not enough stock for {$item['product_name']}");
                        }
                        
                        // Deduct new quantity
                        $product->decrement('stock_quantity', $item['quantity']);
                    }
                    
                    $transactionItem->update([
                        'quantity' => $item['quantity']
                    ]);
                }
            }
            
            // Remove items that were deleted
            $currentItemIds = array_column($this->transactionItems, 'item_id');
            $removedItems = $this->transaction->items()->whereNotIn('item_id', $currentItemIds)->get();
            
            foreach ($removedItems as $removedItem) {
                // Restore stock for removed items
                $product = Product::find($removedItem->product_id);
                if ($product) {
                    $product->increment('stock_quantity', $removedItem->quantity);
                }
                $removedItem->delete();
            }
            
            // Update transaction total and payment method
            $this->transaction->update([
                'total_amount' => $newTotal,
                'payment_method' => $this->paymentMethod
            ]);
            
            // Log the changes
            app(\App\Services\AuditLogService::class)->log(
                action: \App\Enums\AuditAction::TRANSACTION_UPDATED,
                entity: $this->transaction,
                user: Auth::user(),
                details: [
                    'old_total' => $this->transaction->getOriginal('total_amount'),
                    'new_total' => $newTotal,
                    'payment_method' => $this->paymentMethod
                ]
            );
            
            \DB::commit();
            
            session()->flash('success', 'Order updated successfully!');
            return $this->redirect(route('customers.orders.show', $this->transaction), navigate: true);
            
        } catch (\Exception $e) {
            \DB::rollBack();
            
            Flux::toast(
                text: 'Failed to update order: ' . $e->getMessage(),
                variant: 'danger'
            );
        }
    }
    
    public function cancelOrder()
    {
        try {
            \DB::beginTransaction();
            
            // Restore stock for all items
            foreach ($this->transaction->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock_quantity', $item->quantity);
                }
            }
            
            // Update transaction status
            $this->transaction->update([
                'payment_status' => 'cancelled'
            ]);
            
            // Log the cancellation
            app(\App\Services\AuditLogService::class)->log(
                action: \App\Enums\AuditAction::TRANSACTION_CANCELLED,
                entity: $this->transaction,
                user: Auth::user(),
                details: ['reason' => 'customer_cancellation']
            );
            
            \DB::commit();
            
            session()->flash('info', 'Order cancelled successfully.');
            return $this->redirect(route('customers.orders.index'), navigate: true);
            
        } catch (\Exception $e) {
            \DB::rollBack();
            
            Flux::toast(
                text: 'Failed to cancel order: ' . $e->getMessage(),
                variant: 'danger'
            );
        }
    }
    
    private function getTotalAmount(): float
    {
        return array_sum(array_map(function ($item) {
            return $item['unit_price'] * $item['quantity'];
        }, $this->transactionItems));
    }
    
    private function getStats(): array
    {
        return [
            'total_items' => count($this->transactionItems),
            'total_quantity' => array_sum(array_column($this->transactionItems, 'quantity')),
            'subtotal' => $this->getTotalAmount(),
            'total' => $this->getTotalAmount(), // No tax for now
        ];
    }
    
    public function render()
    {
        return view('livewire.customer.orders.edit', [
            'stats' => $this->getStats(),
        ]);
    }
}
