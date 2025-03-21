<?php

namespace App\Livewire\Admin\StockAlert;

use App\Models\StockAlert;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    /** @var string|null Filter by date range (today, week, month) */
    public ?string $dateFilter = null;

    /** @var string|null Filter by threshold level (critical, warning) */
    public ?string $thresholdFilter = null;

    public int $perPage = 10;

    /** @var array<string, string> Stored query string parameters */
    protected $queryString = [
        'search' => ['except' => ''],
        'dateFilter' => ['except' => null],
        'thresholdFilter' => ['except' => null],
        'perPage' => ['except' => 10],
    ];

    /**
     * Reset pagination when filters change
     *
     * @return void
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when date filter changes
     *
     * @return void
     */
    public function updatedDateFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when threshold filter changes
     *
     * @return void
     */
    public function updatedThresholdFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Get filtered and paginated alerts
     *
     * @return LengthAwarePaginator<StockAlert>
     */
    public function getAlertsProperty(): LengthAwarePaginator
    {
        return $this->queryAlerts()->paginate($this->perPage);
    }

    /**
     * Build query for alerts with applied filters
     *
     * @return Builder
     */
    private function queryAlerts(): Builder
    {
        $query = StockAlert::query()
            ->join('products', 'stock_alerts.product_id', '=', 'products.product_id')
            ->select('stock_alerts.*', 'products.name as product_name');

        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->whereLike('products.name', '%' . $this->search . '%')
                    ->orWhereLike('stock_alerts.alert_message', '%' . $this->search . '%');
            });
        }

        if ($this->dateFilter) {
            switch ($this->dateFilter) {
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

        if ($this->thresholdFilter) {
            switch ($this->thresholdFilter) {
                case 'critical':
                    $query->whereRaw('current_quantity <= threshold * 0.5');
                    break;
                case 'warning':
                    $query->whereRaw('current_quantity > threshold * 0.5 AND current_quantity <= threshold');
                    break;
            }
        }

        return $query->orderBy('triggered_at', 'desc');
    }

    /**
     * Render the component
     *
     * @return View
     */
    public function render(): View
    {
        return view('livewire.admin.stock-alert.index', [
            'alerts' => $this->alerts,
        ]);
    }
}
