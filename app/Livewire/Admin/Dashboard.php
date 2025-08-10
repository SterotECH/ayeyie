<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\Pickup;
use App\Models\Product;
use App\Models\SuspiciousActivity;
use App\Models\Transaction;
use App\Models\User;
use App\Services\StockAlertService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class Dashboard extends Component
{
    public function render(): View
    {
        if (Auth::user()?->role !== 'admin') {
            abort(403, 'Access denied');
        }

        // Comprehensive admin stats
        $stats = $this->getAdminStats();
        $recentAuditLogs = $this->getRecentAuditLogs();
        $criticalAlerts = app(StockAlertService::class)->getCriticalAlerts()->take(5);
        $recentSuspiciousActivity = $this->getRecentSuspiciousActivity();
        $systemOverview = $this->getSystemOverview();

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'recentAuditLogs' => $recentAuditLogs,
            'criticalAlerts' => $criticalAlerts,
            'recentSuspiciousActivity' => $recentSuspiciousActivity,
            'systemOverview' => $systemOverview,
        ]);
    }

    private function getAdminStats(): array
    {
        $stockStats = app(StockAlertService::class)->getAlertStatistics();

        return [
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'staff_users' => User::where('role', 'staff')->count(),
            'customer_users' => User::where('role', 'customer')->count(),
            'total_products' => Product::count(),
            'low_stock_products' => Product::whereRaw('stock_quantity <= threshold_quantity')->count(),
            'out_of_stock_products' => Product::where('stock_quantity', 0)->count(),
            'critical_alerts' => $stockStats['critical'] + $stockStats['out_of_stock'],
            'unresolved_alerts' => $stockStats['total_unresolved'],
            'recent_transactions' => Transaction::where('transaction_date', '>=', now()->subDay())->count(),
            'pending_pickups' => Pickup::where('pickup_status', 'pending')->count(),
            'suspicious_activities' => SuspiciousActivity::all()->filter(fn ($s) => ! $s->is_resolved)->count(),
            'high_severity_fraud' => SuspiciousActivity::all()->filter(fn ($s) => $s->severity === 'high' && ! $s->is_resolved)->count(),
        ];
    }

    private function getRecentAuditLogs(): Collection
    {
        return AuditLog::with('user')
            ->orderBy('logged_at', 'desc')
            ->limit(10)
            ->get();
    }

    private function getRecentSuspiciousActivity(): Collection
    {
        return SuspiciousActivity::with(['user'])
            ->orderBy('detected_at', 'desc')
            ->limit(5)
            ->get();
    }

    private function getSystemOverview(): array
    {
        $today = now()->toDateString();
        $thisMonth = now()->format('Y-m');

        return [
            'todays_transactions' => Transaction::whereDate('transaction_date', $today)->count(),
            'todays_revenue' => Transaction::whereDate('transaction_date', $today)
                ->where('payment_status', 'completed')->sum('total_amount'),
            'monthly_transactions' => Transaction::where('transaction_date', 'like', "{$thisMonth}%")->count(),
            'monthly_revenue' => Transaction::where('transaction_date', 'like', "{$thisMonth}%")
                ->where('payment_status', 'completed')->sum('total_amount'),
            'active_users_today' => AuditLog::whereDate('logged_at', $today)
                ->distinct('user_id')->count(),
            'system_health' => 'optimal', // Could be calculated based on error rates, etc.
        ];
    }
}
