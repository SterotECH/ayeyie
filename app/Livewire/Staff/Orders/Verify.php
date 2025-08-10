<?php

declare(strict_types=1);

namespace App\Livewire\Staff\Orders;

use App\Models\Transaction;
use App\Models\Receipt;
use App\Models\Pickup;
use App\Enums\AuditAction;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class Verify extends Component
{
    public ?Transaction $transaction = null;
    public ?Receipt $receipt = null;
    public string $receiptCode = '';
    public int $transactionId = 0;
    public bool $orderFound = false;
    public bool $orderVerified = false;
    public string $customerName = '';
    public string $customerPhone = '';

    public function mount(): void
    {
        // Ensure staff access
        if (Auth::user()?->role !== 'staff') {
            abort(403, 'Staff access required');
        }

        // Get parameters from URL
        $this->receiptCode = request('receipt_code', '');
        $this->transactionId = (int) request('transaction_id', 0);

        if ($this->receiptCode && $this->transactionId) {
            $this->verifyOrder();
        }
    }

    public function verifyOrder(): void
    {
        // Find the receipt and transaction
        $this->receipt = Receipt::where('receipt_code', $this->receiptCode)->first();
        
        if (!$this->receipt || $this->receipt->transaction_id !== $this->transactionId) {
            session()->flash('error', 'Invalid receipt code or transaction ID.');
            return;
        }

        // Load the transaction with relationships
        $this->transaction = $this->receipt->transaction;
        $this->transaction->load(['items.product', 'customer', 'receipt.pickup']);
        
        if (!$this->transaction) {
            session()->flash('error', 'Transaction not found.');
            return;
        }

        $this->orderFound = true;
        $this->customerName = $this->transaction->customer?->name ?? 'Walk-in Customer';
        $this->customerPhone = $this->transaction->customer?->phone ?? 'N/A';
    }

    public function confirmPickup(): void
    {
        if (!$this->transaction || !$this->receipt) {
            session()->flash('error', 'No order to confirm.');
            return;
        }

        try {
            // Find the existing pickup record and complete it
            $pickup = Pickup::where('receipt_id', $this->receipt->receipt_id)->first();
            
            if (!$pickup) {
                session()->flash('error', 'Pickup record not found.');
                return;
            }

            // Update pickup record to completed (keep original preferred date as reference)
            $preferredDate = $pickup->pickup_date; // Store the original preferred date
            $pickup->update([
                'user_id' => Auth::id(),
                'pickup_date' => CarbonImmutable::now(), // Actual pickup completion time
                'pickup_status' => 'completed',
            ]);

            // Update transaction payment status to completed since they're picking up
            $this->transaction->update([
                'payment_status' => 'completed'
            ]);

            // Log the pickup completion
            app(\App\Services\AuditLogService::class)->log(
                action: AuditAction::PICKUP_COMPLETED,
                entity: $pickup,
                user: Auth::user(),
                details: [
                    'transaction_id' => $this->transaction->transaction_id,
                    'receipt_code' => $this->receiptCode,
                    'staff_verified' => true,
                    'payment_method' => $this->transaction->payment_method,
                    'total_amount' => $this->transaction->total_amount,
                    'staff_id' => Auth::id(),
                ]
            );

            $this->orderVerified = true;
            session()->flash('success', 'Order confirmed and pickup completed successfully!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to confirm pickup: ' . $e->getMessage());
            logger()->error('Pickup confirmation failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $this->transaction->transaction_id,
                'staff_id' => Auth::id(),
            ]);
        }
    }

    public function render(): View
    {
        return view('livewire.staff.orders.verify');
    }
}