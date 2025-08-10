<?php

declare(strict_types=1);

namespace App\Livewire\Staff\Transactions;

use App\Enums\AuditAction;
use App\Models\Receipt;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

final class ProcessPayment extends Component
{
    #[Validate('required|string|min:8|max:20')]
    public string $receiptCode = '';

    public ?Transaction $transaction = null;

    public ?Receipt $receipt = null;

    public bool $orderFound = false;

    public bool $paymentProcessed = false;

    public string $customerName = '';

    public string $customerPhone = '';

    #[Validate('required|numeric|min:0')]
    public float $amountReceived = 0.0;

    public float $changeAmount = 0.0;
    
    public bool $isSearching = false;

    public function mount(): void
    {
        // Ensure staff access
        if (Auth::user()?->role !== 'staff') {
            abort(403, 'Staff access required');
        }

        // Get receipt code from URL if provided
        $this->receiptCode = request('receipt_code', '');

        if ($this->receiptCode) {
            $this->findOrder();
        }
    }

    public function findOrder(): void
    {
        $this->isSearching = true;
        
        // Add debugging
        logger()->info('FindOrder called with receipt code: ' . $this->receiptCode);
        
        // Validate receipt code
        $this->validate([
            'receiptCode' => 'required|string|min:8|max:20',
        ], [
            'receiptCode.required' => 'Please enter a receipt code.',
            'receiptCode.min' => 'Receipt code must be at least 8 characters.',
            'receiptCode.max' => 'Receipt code cannot exceed 20 characters.',
        ]);

        // Reset any previous order data
        $this->resetOrderData();

        // Find the receipt and transaction
        $this->receipt = Receipt::where('receipt_code', $this->receiptCode)->first();
        logger()->info('Receipt found: ' . ($this->receipt ? 'Yes' : 'No'));

        if (! $this->receipt) {
            session()->flash('error', 'Receipt not found. Please check the receipt code.');
            $this->resetOrderData();
            $this->isSearching = false;

            return;
        }

        // Load the transaction with relationships
        $this->transaction = $this->receipt->transaction;
        logger()->info('Transaction loaded: ' . ($this->transaction ? 'Yes' : 'No'));
        
        if (! $this->transaction) {
            session()->flash('error', 'Transaction not found.');
            $this->resetOrderData();
            $this->isSearching = false;

            return;
        }
        
        $this->transaction->load(['items.product', 'customer']);
        logger()->info('Transaction payment status: ' . $this->transaction->payment_status);
        logger()->info('Transaction payment method: ' . $this->transaction->payment_method);

        // Check if payment is already completed
        if ($this->transaction->payment_status === 'completed') {
            logger()->info('Payment already completed');
            session()->flash('info', 'This order has already been paid for.');
            $this->resetOrderData();
            $this->isSearching = false;

            return;
        }

        // Check if this is a cash order (cash or cash_on_delivery)
        if (! in_array($this->transaction->payment_method, ['cash', 'cash_on_delivery'], true)) {
            logger()->info('Not a cash order - method: ' . $this->transaction->payment_method);
            session()->flash('error', 'This order was not placed as a cash payment.');
            $this->resetOrderData();
            $this->isSearching = false;

            return;
        }

        $this->orderFound = true;
        $this->customerName = $this->transaction->customer?->name ?? 'Walk-in Customer';
        $this->customerPhone = $this->transaction->customer?->phone ?? 'N/A';
        $this->amountReceived = (float) $this->transaction->total_amount;
        $this->calculateChange();
        
        logger()->info('Order found successfully. Order found flag: ' . ($this->orderFound ? 'true' : 'false'));
        session()->flash('success', 'Order found! Ready for payment processing.');
        
        $this->isSearching = false;
    }

    public function calculateChange(): void
    {
        $this->changeAmount = max(0, $this->amountReceived - (float) $this->transaction->total_amount);
    }

    public function processPayment(): void
    {
        $this->validate(['amountReceived' => 'required|numeric|min:0']);

        if (! $this->transaction || ! $this->receipt) {
            session()->flash('error', 'No order to process.');

            return;
        }

        if ($this->amountReceived < (float) $this->transaction->total_amount) {
            session()->flash('error', 'Amount received is less than the total amount due.');

            return;
        }

        try {
            DB::transaction(function () {
                // Update transaction to mark payment as completed
                $this->transaction->update([
                    'payment_status' => 'completed',
                ]);

                // Log the payment processing
                app(\App\Services\AuditLogService::class)->log(
                    action: AuditAction::PAYMENT_PROCESSED,
                    entity: $this->transaction,
                    user: Auth::user(),
                    details: [
                        'receipt_code' => $this->receiptCode,
                        'total_amount' => $this->transaction->total_amount,
                        'amount_received' => $this->amountReceived,
                        'change_given' => $this->changeAmount,
                        'payment_method' => 'cash',
                        'processed_by_staff' => Auth::id(),
                        'customer_name' => $this->customerName,
                    ],
                );
            });

            $this->paymentProcessed = true;
            session()->flash('success', 'Payment processed successfully!');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to process payment: ' . $e->getMessage());
            logger()->error('Payment processing failed', [
                'error' => $e->getMessage(),
                'transaction_id' => $this->transaction->transaction_id,
                'staff_id' => Auth::id(),
                'receipt_code' => $this->receiptCode,
            ]);
        }
    }

    public function resetForm(): void
    {
        $this->resetOrderData();
        $this->receiptCode = '';
        $this->paymentProcessed = false;
        session()->forget(['error', 'success', 'info']);
    }


    public function render()
    {
        return view('livewire.staff.transactions.process-payment');
    }

    private function resetOrderData(): void
    {
        $this->transaction = null;
        $this->receipt = null;
        $this->orderFound = false;
        $this->customerName = '';
        $this->customerPhone = '';
        $this->amountReceived = 0.0;
        $this->changeAmount = 0.0;
    }
}
