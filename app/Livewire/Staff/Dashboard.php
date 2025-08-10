<?php

namespace App\Livewire\Staff;

use App\Models\AuditLog;
use App\Models\Pickup;
use App\Models\Product;
use App\Models\StockAlert;
use App\Models\Transaction;
use App\Services\StockAlertService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        // Only show staff dashboard for staff users
        if (Auth::user()?->role !== 'staff') {
            abort(403, 'Access denied');
        }

        // Staff-specific stats (limited scope)
        $stats = $this->getStaffStats();
        $recentActivity = $this->getRecentActivity();
        $urgentAlerts = app(StockAlertService::class)->getCriticalAlerts()->take(3);
        $todaysOverview = $this->getTodaysOverview();

        return view('livewire.staff.dashboard', [
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'urgentAlerts' => $urgentAlerts,
            'todaysOverview' => $todaysOverview,
        ]);
    }

    private function getStaffStats(): array
    {
        $stockStats = app(StockAlertService::class)->getAlertStatistics();

        return [
            'total_products' => Product::count(),
            'low_stock_products' => Product::whereRaw('stock_quantity <= threshold_quantity')->count(),
            'out_of_stock_products' => Product::where('stock_quantity', 0)->count(),
            'urgent_alerts' => $stockStats['critical'] + $stockStats['out_of_stock'],
            'pending_pickups' => Pickup::where('pickup_status', 'pending')->count(),
            'todays_transactions' => Transaction::whereDate('transaction_date', now())->count(),
        ];
    }

    private function getRecentActivity(): \Illuminate\Database\Eloquent\Collection
    {
        // Show only operational activities, not administrative ones
        return AuditLog::with('user')
            ->whereIn('action', [
                'product_updated', 'transaction_processed', 'pickup_updated',
                'stock_updated', 'order_fulfilled'
            ])
            ->orderBy('logged_at', 'desc')
            ->limit(5)
            ->get();
    }

    private function getTodaysOverview(): array
    {
        $today = now()->toDateString();

        return [
            'transactions' => Transaction::whereDate('transaction_date', $today)->count(),
            'completed_pickups' => Pickup::whereDate('pickup_date', $today)
                ->where('pickup_status', 'completed')->count(),
            'pending_pickups' => Pickup::where('pickup_status', 'pending')->count(),
            'revenue' => Transaction::whereDate('transaction_date', $today)
                ->where('payment_status', 'completed')->sum('total_amount'),
            'my_activities' => AuditLog::where('user_id', Auth::id())
                ->whereDate('logged_at', $today)->count(),
        ];
    }
}
