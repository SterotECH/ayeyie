<?php

namespace App\Livewire\Customer;

use App\Models\Pickup;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Only show customer dashboard for customer users
        if (Auth::user()?->role !== 'customer') {
            abort(403, 'Access denied');
        }

        $customerId = Auth::id();
        
        // Customer-specific data
        $stats = $this->getCustomerStats($customerId);
        $recentOrders = $this->getRecentOrders($customerId);
        $upcomingPickups = $this->getUpcomingPickups($customerId);
        $orderSummary = $this->getOrderSummary($customerId);

        return view('livewire.customer.dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'upcomingPickups' => $upcomingPickups,
            'orderSummary' => $orderSummary,
        ]);
    }

    private function getCustomerStats(int $customerId): array
    {
        return [
            'total_orders' => Transaction::where('customer_id', $customerId)->count(),
            'pending_orders' => Transaction::where('customer_id', $customerId)
                ->where('transaction_status', 'pending')->count(),
            'completed_orders' => Transaction::where('customer_id', $customerId)
                ->where('transaction_status', 'completed')->count(),
            'total_spent' => Transaction::where('customer_id', $customerId)
                ->where('payment_status', 'completed')->sum('total_amount'),
            'pending_pickups' => Pickup::where('customer_id', $customerId)
                ->where('pickup_status', 'pending')->count(),
            'completed_pickups' => Pickup::where('customer_id', $customerId)
                ->where('pickup_status', 'completed')->count(),
        ];
    }

    private function getRecentOrders(int $customerId): \Illuminate\Database\Eloquent\Collection
    {
        return Transaction::with(['transactionItems.product'])
            ->where('customer_id', $customerId)
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();
    }

    private function getUpcomingPickups(int $customerId): \Illuminate\Database\Eloquent\Collection
    {
        return Pickup::with(['transaction.transactionItems.product'])
            ->where('customer_id', $customerId)
            ->where('pickup_status', 'pending')
            ->orderBy('pickup_date', 'asc')
            ->limit(3)
            ->get();
    }

    private function getOrderSummary(int $customerId): array
    {
        $thisMonth = now()->format('Y-m');
        $lastMonth = now()->subMonth()->format('Y-m');
        
        $thisMonthSpent = Transaction::where('customer_id', $customerId)
            ->where('transaction_date', 'like', "{$thisMonth}%")
            ->where('payment_status', 'completed')
            ->sum('total_amount');
            
        $lastMonthSpent = Transaction::where('customer_id', $customerId)
            ->where('transaction_date', 'like', "{$lastMonth}%")
            ->where('payment_status', 'completed')
            ->sum('total_amount');
        
        return [
            'this_month_orders' => Transaction::where('customer_id', $customerId)
                ->where('transaction_date', 'like', "{$thisMonth}%")->count(),
            'this_month_spent' => $thisMonthSpent,
            'last_month_spent' => $lastMonthSpent,
            'spending_trend' => $lastMonthSpent > 0 
                ? (($thisMonthSpent - $lastMonthSpent) / $lastMonthSpent) * 100 
                : 0,
        ];
    }
}