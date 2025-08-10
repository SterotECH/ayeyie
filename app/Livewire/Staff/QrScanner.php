<?php

declare(strict_types=1);

namespace App\Livewire\Staff;

use App\Models\Receipt;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class QrScanner extends Component
{
    public string $mode = 'payment'; // 'payment' or 'pickup'
    public string $receiptCode = '';
    public int $transactionId = 0;
    public ?Transaction $transaction = null;
    public ?Receipt $receipt = null;
    public bool $orderFound = false;
    public string $customerName = '';
    public string $customerPhone = '';
    public string $redirectUrl = '';

    public function mount(string $mode = 'payment'): void
    {
        // Ensure staff access
        if (Auth::user()?->role !== 'staff') {
            abort(403, 'Staff access required');
        }

        $this->mode = $mode;

        // Pre-fill from URL parameters if provided
        $this->receiptCode = request('receipt_code', '');
        $this->transactionId = (int) request('transaction_id', 0);

        if ($this->receiptCode || $this->transactionId) {
            $this->processScannedData();
        }
    }

    public function processQrData(string $qrData): void
    {
        // Parse QR code data - expecting format like "AYE2508101234|123" (receipt_code|transaction_id)
        // Or a URL containing these parameters

        if (str_contains($qrData, '|')) {
            // Direct format: receipt_code|transaction_id
            [$receiptCode, $transactionId] = explode('|', $qrData, 2);
            $this->receiptCode = trim($receiptCode);
            $this->transactionId = (int) trim($transactionId);
        } elseif (str_contains($qrData, 'receipt_code=')) {
            // URL format: parse query parameters
            $urlParts = parse_url($qrData);
            if (isset($urlParts['query'])) {
                parse_str($urlParts['query'], $params);
                $this->receiptCode = $params['receipt_code'] ?? '';
                $this->transactionId = (int) ($params['transaction_id'] ?? 0);
            }
        } else {
            // Assume it's just a receipt code
            $this->receiptCode = trim($qrData);
        }

        $this->processScannedData();
    }

    public function processScannedData(): void
    {
        if (empty($this->receiptCode)) {
            session()->flash('error', 'Invalid QR code format. Receipt code is required.');
            return;
        }

        // Find the receipt
        $this->receipt = Receipt::where('receipt_code', $this->receiptCode)->first();

        if (!$this->receipt) {
            session()->flash('error', 'Receipt not found. Please verify the QR code is valid.');
            return;
        }

        // If transaction ID is provided, verify it matches
        if ($this->transactionId > 0 && $this->receipt->transaction_id !== $this->transactionId) {
            session()->flash('error', 'Receipt code and transaction ID do not match.');
            return;
        }

        // Load the transaction
        $this->transaction = $this->receipt->transaction;
        $this->transaction->load(['items.product', 'customer', 'receipt.pickup']);

        if (!$this->transaction) {
            session()->flash('error', 'Transaction not found.');
            return;
        }

        // Mode-specific validation
        if ($this->mode === 'pickup') {
            // For pickup, payment must be completed
            if ($this->transaction->payment_status !== 'completed') {
                session()->flash('error', 'Payment not completed. Please process payment first.');
                session()->flash('info', 'Redirecting to payment processing...');
                $this->redirectUrl = route('staff.transactions.process-payment', ['receipt_code' => $this->receiptCode]);
                return;
            }

            // Check if already picked up
            $pickup = $this->transaction->receipt->pickup;
            if ($pickup && $pickup->pickup_status === 'completed') {
                session()->flash('warning', 'This order has already been picked up.');
                return;
            }
        } elseif ($this->mode === 'payment') {
            // For payment, check if already completed
            if ($this->transaction->payment_status === 'completed') {
                session()->flash('info', 'Payment already completed. Redirecting to pickup verification...');
                $this->redirectUrl = route('staff.orders.verify', [
                    'receipt_code' => $this->receiptCode,
                    'transaction_id' => $this->transaction->transaction_id
                ]);
                return;
            }

            // Must be cash on delivery
            if ($this->transaction->payment_method !== 'cash_on_delivery') {
                session()->flash('error', 'This order was not placed as cash on delivery.');
                return;
            }
        }

        $this->orderFound = true;
        $this->customerName = $this->transaction->customer?->name ?? 'Walk-in Customer';
        $this->customerPhone = $this->transaction->customer?->phone ?? 'N/A';

        // Set redirect URL based on mode
        if ($this->mode === 'payment') {
            $this->redirectUrl = route('staff.transactions.process-payment', ['receipt_code' => $this->receiptCode]);
        } else {
            $this->redirectUrl = route('staff.orders.verify', [
                'receipt_code' => $this->receiptCode,
                'transaction_id' => $this->transaction->transaction_id
            ]);
        }

        // Auto-redirect after showing the order details for 3 seconds
        $this->dispatch('auto-redirect-after-delay');
    }

    public function proceedToAction(): void
    {
        if ($this->redirectUrl) {
            $this->redirect($this->redirectUrl, navigate: true);
        }
    }

    public function resetScanner(): void
    {
        $this->receiptCode = '';
        $this->transactionId = 0;
        $this->transaction = null;
        $this->receipt = null;
        $this->orderFound = false;
        $this->customerName = '';
        $this->customerPhone = '';
        $this->redirectUrl = '';
        session()->forget(['error', 'success', 'info', 'warning']);
    }

    public function render(): View
    {
        return view('livewire.staff.qr-scanner');
    }
}
