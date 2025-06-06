<?php

namespace App\Livewire\Customer\Orders;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Transaction $transaction;
    public $transactionItems;
    public $receipt;
    public $pickup;
    public $customer;
    public $staff;
    public $canBePickedUp = false;
    public $showQrCode = false;

    protected $listeners = ['refreshOrder' => '$refresh'];

    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function loadTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction->load([
            'items.product',
            'receipt',
            'receipt.pickup',
            'customer',
            'staff'
        ]);

        $this->transactionItems = $this->transaction->items;
        $this->receipt = $this->transaction->receipt;
        $this->pickup = $this->receipt->pickup ?? null;
        $this->customer = $this->transaction->customer;
        $this->staff = $this->transaction->staff;

        // Check if the order can be picked up (has a receipt and payment is completed)
        $this->canBePickedUp = $this->receipt &&
            $this->transaction->payment_status === 'completed' &&
            (!$this->pickup || $this->pickup->pickup_status === 'pending');
    }

    public function toggleQrCode()
    {
        $this->showQrCode = !$this->showQrCode;
    }

    public function markAsPickedUp()
    {
        if (!$this->canBePickedUp) {
            return;
        }

        if (!$this->pickup) {
            // Create a new pickup record
            $this->receipt->pickup()->create([
                'user_id' => Auth::id(),
                'pickup_status' => 'completed',
                'pickup_date' => now(),
                'is_synced' => false
            ]);
        } else {
            // Update existing pickup record
            $this->pickup->update([
                'user_id' => Auth::id(),
                'pickup_status' => 'completed',
                'pickup_date' => now(),
                'is_synced' => false
            ]);
        }

        $this->loadTransaction($this->transaction);
        $this->emit('orderPickedUp', $this->transaction->transaction_id);
        session()->flash('message', 'Order has been marked as picked up.');
    }

    public function render()
    {
        $this->loadTransaction($this->transaction);

        return view('livewire.customer.orders.show');
    }
}
