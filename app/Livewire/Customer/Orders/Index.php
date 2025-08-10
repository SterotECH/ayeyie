<?php

namespace App\Livewire\Customer\Orders;

use App\Models\Transaction;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public array $filters = [
        'dateFilter' => '',
        'statusFilter' => ''
    ];
    public string $sortBy = 'transaction_date';
    public string $sortDirection = 'desc';
    public int $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'filters' => ['except' => []],
        'perPage' => ['except' => 15],
    ];

    public function mount()
    {
        // Only show customer orders for customer users
        if (Auth::user()?->role !== 'customer') {
            abort(403, 'Access denied');
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

    public function getTransactionsProperty()
    {
        return $this->fetchTransactions();
    }

    private function fetchTransactions()
    {
        $query = Transaction::query()
            ->where('customer_user_id', Auth::id())
            ->with(['items.product', 'staff', 'receipt'])
            ->orderBy($this->sortBy, $this->sortDirection);

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('transaction_id', 'like', '%' . $this->search . '%')
                  ->orWhereHas('items.product', function ($subQ) {
                      $subQ->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply status filter
        if (!empty($this->filters['statusFilter'])) {
            $query->where('payment_status', $this->filters['statusFilter']);
        }

        // Apply date filter
        if (!empty($this->filters['dateFilter'])) {
            $now = CarbonImmutable::now();

            switch ($this->filters['dateFilter']) {
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

        return $query->paginate($this->perPage);
    }

    private function getStats(): array
    {
        $customerId = Auth::id();

        return [
            'total_orders' => Transaction::where('customer_user_id', $customerId)->count(),
            'pending_orders' => Transaction::where('customer_user_id', $customerId)->where('payment_status', 'pending')->count(),
            'completed_orders' => Transaction::where('customer_user_id', $customerId)->where('payment_status', 'completed')->count(),
            'total_spent' => Transaction::where('customer_user_id', $customerId)->where('payment_status', 'completed')->sum('total_amount'),
        ];
    }

    public function render()
    {
        return view('livewire.customer.orders.index', [
            'transactions' => $this->transactions,
            'stats' => $this->getStats(),
        ]);
    }
}
