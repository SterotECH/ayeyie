<?php

namespace App\Livewire\Customer\Pickup;

use App\Models\Pickup;
use App\Models\Transaction;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Properties
    public string $search = '';
    public array $filters = [
        'status' => '',
        'dateRange' => ''
    ];
    public string $sortBy = 'pickup_date';
    public string $sortDirection = 'desc';
    public int $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'filters' => ['except' => []],
        'perPage' => ['except' => 15],
    ];

    public function mount(): void
    {
        // Only show customer pickups for customer users
        if (Auth::user()?->role !== 'customer') {
            abort(403, 'Access denied');
        }
    }

    #[Computed]
    public function pickups(): LengthAwarePaginator
    {
        $query = Pickup::query()
            ->whereHas('receipt.transaction', function (Builder $query): void {
                $query->where('customer_user_id', Auth::id());
            })
            ->with(['receipt.transaction.items.product', 'user']);

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('receipt', function ($subQ) {
                    $subQ->where('receipt_code', 'like', '%' . $this->search . '%');
                })->orWhere('pickup_id', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->filters['status']) {
            $query->where('pickup_status', $this->filters['status']);
        }

        // Apply date range filter
        if ($this->filters['dateRange']) {
            switch ($this->filters['dateRange']) {
                case 'today':
                    $query->whereDate('pickup_date', now());
                    break;
                case 'week':
                    $query->whereBetween('pickup_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('pickup_date', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
            }
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
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

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilters(): void
    {
        $this->resetPage();
    }

    private function getStats(): array
    {
        $customerId = Auth::id();

        return [
            'total_pickups' => Pickup::whereHas('receipt.transaction', function ($q) use ($customerId) {
                $q->where('customer_user_id', $customerId);
            })->count(),
            'pending_pickups' => Pickup::whereHas('receipt.transaction', function ($q) use ($customerId) {
                $q->where('customer_user_id', $customerId);
            })->where('pickup_status', 'pending')->count(),
            'completed_pickups' => Pickup::whereHas('receipt.transaction', function ($q) use ($customerId) {
                $q->where('customer_user_id', $customerId);
            })->where('pickup_status', 'completed')->count(),
            'todays_pickups' => Pickup::whereHas('receipt.transaction', function ($q) use ($customerId) {
                $q->where('customer_user_id', $customerId);
            })->whereDate('pickup_date', now())->count(),
        ];
    }

    public function render()
    {
        return view('livewire.customer.pickup.index', [
            'pickups' => $this->pickups,
            'stats' => $this->getStats(),
        ]);
    }
}
