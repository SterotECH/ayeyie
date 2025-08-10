<?php

namespace App\Livewire\Customer\Pickup;

use App\Models\Pickup;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Pickup $pickup;

    public function mount(Pickup $pickup)
    {
        // Ensure customer can only view their own pickups
        if (Auth::user()?->role !== 'customer') {
            abort(403, 'Access denied');
        }

        // Load relationships
        $pickup->load([
            'receipt.transaction.transactionItems.product',
            'receipt.transaction.customer',
            'user'
        ]);

        // Verify this pickup belongs to the authenticated customer
        if ($pickup->receipt->transaction->customer_user_id !== Auth::id()) {
            abort(403, 'This pickup does not belong to you');
        }

        $this->pickup = $pickup;
    }

    public function contactStore()
    {
        // This would typically integrate with a messaging system or redirect to contact
        session()->flash('message', 'Contact information will be provided via email or SMS.');
    }

    private function getPickupStats(): array
    {
        $transaction = $this->pickup->receipt->transaction;
        
        return [
            'total_items' => $transaction->transactionItems->count(),
            'total_value' => $transaction->total_amount,
            'order_date' => $transaction->transaction_date,
            'payment_status' => $transaction->payment_status,
            'transaction_method' => $transaction->payment_method,
        ];
    }

    public function render()
    {
        return view('livewire.customer.pickup.show', [
            'stats' => $this->getPickupStats(),
        ]);
    }
}