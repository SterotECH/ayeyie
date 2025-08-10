<?php

declare(strict_types=1);

namespace App\Livewire\Staff\Pickups;

use App\Models\Pickup;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;

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
            'status' => '',
            'dateRange' => ''
        ];
        $this->search = '';
        $this->resetPage();
    }

    private function getStats(): array
    {
        $pending = Pickup::where('pickup_status', 'pending')->count();
        $completed = Pickup::where('pickup_status', 'completed')->count();
        $overdue = Pickup::where('pickup_status', 'pending')
            ->where('pickup_date', '<', now())->count();
        $today = Pickup::whereDate('pickup_date', today())->count();

        return [
            'pending' => $pending,
            'completed' => $completed,
            'overdue' => $overdue,
            'today' => $today,
        ];
    }

    public function render(): View
    {
        $pickups = Pickup::query()
            ->with(['receipt.transaction.customer', 'receipt.transaction.items.product', 'user'])
            ->when($this->search, function ($query) {
                return $query->whereHas('receipt.transaction', function ($q) {
                    $q->where('transaction_id', 'like', '%' . $this->search . '%');
                })->orWhereHas('receipt', function ($q) {
                    $q->where('receipt_code', 'like', '%' . $this->search . '%');
                })->orWhereHas('receipt.transaction.customer', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filters['status'], function ($query) {
                return $query->where('pickup_status', $this->filters['status']);
            })
            ->when($this->filters['dateRange'], function ($query) {
                return match ($this->filters['dateRange']) {
                    'today' => $query->whereDate('pickup_date', today()),
                    'week' => $query->whereBetween('pickup_date', [now()->startOfWeek(), now()->endOfWeek()]),
                    'overdue' => $query->where('pickup_status', 'pending')->where('pickup_date', '<', now()),
                    default => $query
                };
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.staff.pickups.index', [
            'pickups' => $pickups,
            'stats' => $this->getStats(),
        ]);
    }
}
