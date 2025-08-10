<?php

declare(strict_types=1);

namespace App\Livewire\Staff\StockAlerts;

use App\Models\StockAlert;
use App\Services\StockAlertService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public array $filters = [
        'severity' => '',
        'status' => ''
    ];
    public string $sortBy = 'triggered_at';
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
            'severity' => '',
            'status' => ''
        ];
        $this->search = '';
        $this->resetPage();
    }

    public function acknowledgeAlert(int $alertId): void
    {
        $alert = StockAlert::findOrFail($alertId);

        if ($alert->status === 'active') {
            $alert->update([
                'status' => 'acknowledged',
                'acknowledged_at' => now(),
                'acknowledged_by' => Auth::id(),
            ]);

            session()->flash('success', 'Alert acknowledged successfully.');
        }
    }

    public function resolveAlert(int $alertId): void
    {
        $alert = StockAlert::findOrFail($alertId);

        if (in_array($alert->status, ['active', 'acknowledged'])) {
            $alert->update([
                'status' => 'resolved',
                'resolved_at' => now(),
                'resolved_by' => Auth::id(),
            ]);

            session()->flash('success', 'Alert marked as resolved.');
        }
    }

    private function getStats(): array
    {
        return app(StockAlertService::class)->getAlertStatistics();
    }

    public function render(): View
    {
        $alerts = StockAlert::query()
            ->with(['product', 'acknowledgedBy', 'resolvedBy'])
            ->when($this->search, function ($query) {
                return $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filters['severity'], function ($query) {
                return $query->where('alert_level', $this->filters['severity']);
            })
            ->when($this->filters['status'], function ($query) {
                return $query->where('status', $this->filters['status']);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.staff.stock-alerts.index', [
            'alerts' => $alerts,
            'stats' => $this->getStats(),
        ]);
    }
}
