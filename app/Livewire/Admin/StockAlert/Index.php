<?php

namespace App\Livewire\Admin\StockAlert;

use App\Enums\StockAlertLevel;
use App\Models\StockAlert;
use App\Services\StockAlertService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function __construct(
        private readonly StockAlertService $stockAlertService
    ) {}

    public string $search = '';
    public array $filters = [
        'dateFilter' => '',
        'thresholdFilter' => '',
        'resolvedFilter' => ''
    ];
    public string $sortBy = 'triggered_at';
    public string $sortDirection = 'desc';
    public int $perPage = 15;

    public bool $showBulkActions = false;
    public array $selectedAlerts = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'filters' => ['except' => []],
        'perPage' => ['except' => 15],
    ];

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

    /**
     * Resolve a single stock alert
     */
    public function resolveAlert(int $alertId): void
    {
        $alert = StockAlert::find($alertId);
        
        if ($alert && !$alert->is_resolved) {
            $alert->markAsResolved(Auth::id());

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Stock alert resolved successfully!'
            ]);
        }
    }

    /**
     * Check all products for stock alerts
     */
    public function checkAllProductsStock(): void
    {
        $alerts = $this->stockAlertService->checkAllProductsStock(Auth::user());
        
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => "Stock check completed. {$alerts->count()} alerts generated."
        ]);
    }

    /**
     * Toggle bulk actions display
     */
    public function toggleBulkActions(): void
    {
        $this->showBulkActions = !$this->showBulkActions;
        if (!$this->showBulkActions) {
            $this->selectedAlerts = [];
        }
    }

    private function getStats(): array
    {
        return $this->stockAlertService->getAlertStatistics();
    }

    /**
     * Get filtered and paginated alerts
     *
     * @return LengthAwarePaginator<StockAlert>
     */
    public function getAlertsProperty(): LengthAwarePaginator
    {
        $query = $this->queryAlerts();
        $alerts = $query->get();
        
        // Apply filters that need to be done in memory
        if ($this->filters['resolvedFilter'] !== '') {
            $isResolved = (bool) $this->filters['resolvedFilter'];
            $alerts = $alerts->filter(fn($alert) => $alert->is_resolved === $isResolved);
        }
        
        // Manual pagination
        $total = $alerts->count();
        $alerts = $alerts->slice(($this->getPage() - 1) * $this->perPage, $this->perPage)->values();
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $alerts,
            $total,
            $this->perPage,
            $this->getPage(),
            ['path' => request()->url()]
        );
    }

    /**
     * Build query for alerts with applied filters
     *
     * @return Builder
     */
    private function queryAlerts(): Builder
    {
        $query = StockAlert::query()
            ->with(['product', 'resolvedBy']);

        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('alert_message', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filters['dateFilter']) {
            switch ($this->filters['dateFilter']) {
                case 'today':
                    $query->whereDate('triggered_at', now()->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('triggered_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('triggered_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
            }
        }

        // Note: Alert level filtering will be done in memory since it's computed from JSON

        // Note: Resolution status filtering is done in memory since it's stored in JSON

        // Basic ordering (complex sorting done in memory)
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query;
    }

    public function render(): View
    {
        return view('livewire.admin.stock-alert.index', [
            'alerts' => $this->alerts,
            'stats' => $this->getStats(),
            'alertLevels' => StockAlertLevel::cases(),
        ]);
    }
}
