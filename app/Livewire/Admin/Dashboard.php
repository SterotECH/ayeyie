<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\Pickup;
use App\Models\StockAlert;
use App\Models\SuspiciousActivity;
use App\Models\Transaction;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Fetch stats
        $fraudCount = SuspiciousActivity::where('severity', 'high')->count();
        $stockAlertCount = StockAlert::count();
        $pendingPickupCount = Pickup::where('pickup_status', 'pending')->count();
        $recentTransactionCount = Transaction::where('transaction_date', '>=', now()->subDay())->count();

        // Fetch recent audit logs (last 5)
        $recentAuditLogs = AuditLog::with('user')
            ->orderBy('logged_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'fraudCount' => $fraudCount,
            'stockAlertCount' => $stockAlertCount,
            'pendingPickupCount' => $pendingPickupCount,
            'recentTransactionCount' => $recentTransactionCount,
            'recentAuditLogs' => $recentAuditLogs,
        ]);
    }
}
