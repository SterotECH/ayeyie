<?php

declare(strict_types=1);

namespace App\Livewire\Staff\Transactions;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public array $filters = [
        'payment_status' => '',
        'payment_method' => '',
        'dateRange' => ''
    ];
    public string $sortBy = 'transaction_date';
    public string $sortDirection = 'desc';
    public int $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'filters' => ['except' => []],
        'perPage' => ['except' => 15],
    ];

    public function mount(): void
    {
        // Ensure staff access
        if (Auth::user()?->role !== 'staff') {
            abort(403, 'Staff access required');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilters(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters(): void
    {
        $this->filters = [
            'payment_status' => '',
            'payment_method' => '',
            'dateRange' => ''
        ];
        $this->search = '';
        $this->resetPage();
    }

    private function getStats(): array
    {
        $today = now()->toDateString();
        
        return [
            'total' => Transaction::count(),
            'completed' => Transaction::where('payment_status', 'completed')->count(),
            'pending' => Transaction::where('payment_status', 'pending')->count(),
            'today_revenue' => Transaction::whereDate('transaction_date', $today)
                ->where('payment_status', 'completed')
                ->sum('total_amount'),
        ];
    }

    public function render()
    {
        $transactions = Transaction::query()
            ->with(['customer', 'items.product', 'receipt.pickup'])
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('transaction_id', 'like', '%' . $this->search . '%')
                      ->orWhereHas('customer', function ($customerQuery) {
                          $customerQuery->where('name', 'like', '%' . $this->search . '%')
                                       ->orWhere('phone', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('receipt', function ($receiptQuery) {
                          $receiptQuery->where('receipt_code', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->filters['payment_status'], function ($query) {
                return $query->where('payment_status', $this->filters['payment_status']);
            })
            ->when($this->filters['payment_method'], function ($query) {
                return $query->where('payment_method', $this->filters['payment_method']);
            })
            ->when($this->filters['dateRange'], function ($query) {
                return match ($this->filters['dateRange']) {
                    'today' => $query->whereDate('transaction_date', today()),
                    'week' => $query->whereBetween('transaction_date', [now()->startOfWeek(), now()->endOfWeek()]),
                    'month' => $query->whereBetween('transaction_date', [now()->startOfMonth(), now()->endOfMonth()]),
                    default => $query
                };
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.staff.transactions.index', [
            'transactions' => $transactions,
            'stats' => $this->getStats(),
        ]);
    }
}