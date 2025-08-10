<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Orders;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class Show extends Component
{
    public Transaction $transaction;

    public function mount(Transaction $transaction): void
    {
        // Ensure customer can only view their own orders
        if (Auth::user()?->role !== 'customer') {
            abort(403, 'Access denied');
        }

        // Load relationships
        $transaction->load([
            'items.product',
            'receipt.pickup',
            'customer',
            'staff',
        ]);

        // Verify this order belongs to the authenticated customer
        if ($transaction->customer_user_id !== Auth::id()) {
            abort(403, 'This order does not belong to you');
        }

        $this->transaction = $transaction;
    }

    public function contactStore(): void
    {
        // This would typically integrate with a messaging system or redirect to contact
        session()->flash('message', 'Contact information will be provided via email or SMS.');
    }
    
    
    public function requestDelivery(): void
    {
        if ($this->transaction->payment_method !== 'cash' || $this->transaction->payment_status !== 'pending') {
            session()->flash('error', 'Invalid action for this order.');
            return;
        }
        
        // Log delivery request
        app(\App\Services\AuditLogService::class)->log(
            action: \App\Enums\AuditAction::DELIVERY_REQUESTED,
            entity: $this->transaction,
            user: Auth::user(),
            details: [
                'payment_method' => $this->transaction->payment_method,
                'amount' => $this->transaction->total_amount,
                'customer_request' => 'paid_delivery_request'
            ]
        );
        
        session()->flash('success', 'Paid delivery request submitted. We will contact you within 24 hours with delivery fee details and schedule delivery.');
    }

    public function render(): View
    {
        return view('livewire.customer.orders.show', [
            'stats' => $this->getOrderStats(),
        ]);
    }

    private function getOrderStats(): array
    {
        return [
            'total_items' => $this->transaction->items->count(),
            'total_value' => $this->transaction->total_amount,
            'order_date' => $this->transaction->transaction_date,
            'payment_status' => $this->transaction->payment_status,
            'payment_method' => $this->transaction->payment_method,
            'has_pickup' => (bool) $this->transaction->receipt?->pickup,
            'pickup_status' => $this->transaction->receipt?->pickup?->pickup_status,
        ];
    }
}
