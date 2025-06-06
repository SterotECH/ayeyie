<?php

namespace App\Livewire\Customer\Orders;

use App\Models\Transaction;
use Carbon\CarbonImmutable;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFilter = '';
    public $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedDateFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function getTransactionsProperty()
    {
        return $this->fetchTransactions();
    }

    private function fetchTransactions()
    {
        $query = Transaction::query()
            // ->with('customer')
            ->orderBy('transaction_date', 'desc');

        // Apply search filter
        if (!empty($this->search)) {
            $query->whereLike('transaction_id', '%' . $this->search . '%');
        }

        // Apply status filter
        if (!empty($this->statusFilter)) {
            $query->where('payment_status', $this->statusFilter);
        }

        // Apply date filter
        if (!empty($this->dateFilter)) {
            $now = CarbonImmutable::now();

            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('transaction_date', $now->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('transaction_date', $now->subDay()->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('transaction_date', [
                        $now->startOfWeek()->toDateTimeString(),
                        $now->endOfWeek()->toDateTimeString()
                    ]);
                    break;
                case 'this_month':
                    $query->whereBetween('transaction_date', [
                        $now->startOfMonth()->toDateTimeString(),
                        $now->endOfMonth()->toDateTimeString()
                    ]);
                    break;
                case 'last_month':
                    $lastMonth = CarbonImmutable::now()->subMonth();
                    $query->whereBetween('transaction_date', [
                        $lastMonth->startOfMonth()->toDateTimeString(),
                        $lastMonth->endOfMonth()->toDateTimeString()
                    ]);
                    break;
            }
        }

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.customer.orders.index', [
            'transactions' => $this->transactions
        ]);
    }
}
