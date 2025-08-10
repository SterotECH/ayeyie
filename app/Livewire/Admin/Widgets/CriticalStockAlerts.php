<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Widgets;

use App\Services\StockAlertService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class CriticalStockAlerts extends Component
{
    public function __construct(
        private readonly StockAlertService $stockAlertService
    ) {}

    public int $displayLimit = 5;
    public bool $autoRefresh = true;

    /**
     * Refresh the component data
     */
    public function refresh(): void
    {
        $this->dispatch('$refresh');
    }

    /**
     * Mark alert as acknowledged (for dashboard purposes)
     */
    public function acknowledgeAlert(int $alertId): void
    {
        // You could add an acknowledgment feature to the StockAlert model
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => 'Alert acknowledged. Please resolve through Stock Alert Management.'
        ]);
    }

    public function render(): View
    {
        $criticalAlerts = $this->stockAlertService->getCriticalAlerts()
            ->take($this->displayLimit);

        $stats = $this->stockAlertService->getAlertStatistics();

        return view('livewire.admin.widgets.critical-stock-alerts', [
            'criticalAlerts' => $criticalAlerts,
            'totalCritical' => $stats['critical'] + $stats['out_of_stock'],
            'totalUnresolved' => $stats['total_unresolved'],
            'hasMore' => $stats['total_unresolved'] > $this->displayLimit,
        ]);
    }
}