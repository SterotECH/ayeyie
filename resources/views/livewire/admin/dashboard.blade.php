<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-[#FDFDFC] dark:bg-[#1b1b18] p-6 rounded-lg shadow-sm border border-[#19140035] dark:border-[#3E3E3A]">
            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 flex items-center">
                <svg class="size-7 text-amber-600 dark:text-amber-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z" />
                </svg>
                Fraud Alerts
            </h3>
            <p class="text-3xl text-amber-600 dark:text-amber-400 font-bold">{{ $fraudCount }}</p>
            <p class="text-sm text-[#4a4a47] dark:text-[#A2A29D]">High-severity incidents</p>
        </div>
        <div class="bg-[#FDFDFC] dark:bg-[#1b1b18] p-6 rounded-lg shadow-sm border border-[#19140035] dark:border-[#3E3E3A]">
            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 flex items-center">
                <svg class="size-7 text-amber-600 dark:text-amber-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Stock Alerts
            </h3>
            <p class="text-3xl text-amber-600 dark:text-amber-400 font-bold">{{ $stockAlertCount }}</p>
            <p class="text-sm text-[#4a4a47] dark:text-[#A2A29D]">Low stock items</p>
        </div>
        <div class="bg-[#FDFDFC] dark:bg-[#1b1b18] p-6 rounded-lg shadow-sm border border-[#19140035] dark:border-[#3E3E3A]">
            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 flex items-center">
                <svg class="size-7 text-amber-600 dark:text-amber-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Pending Pickups
            </h3>
            <p class="text-3xl text-amber-600 dark:text-amber-400 font-bold">{{ $pendingPickupCount }}</p>
            <p class="text-sm text-[#4a4a47] dark:text-[#A2A29D]">Awaiting verification</p>
        </div>
        <div class="bg-[#FDFDFC] dark:bg-[#1b1b18] p-6 rounded-lg shadow-sm border border-[#19140035] dark:border-[#3E3E3A]">
            <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2 flex items-center">
                <svg class="size-7 text-amber-600 dark:text-amber-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Transactions
            </h3>
            <p class="text-3xl text-amber-600 dark:text-amber-400 font-bold">{{ $recentTransactionCount }}</p>
            <p class="text-sm text-[#4a4a47] dark:text-[#A2A29D]">Processed today</p>
        </div>
    </div>

    <!-- Recent Audit Logs -->
    <div class="bg-[#FDFDFC] dark:bg-[#1b1b18] p-6 rounded-lg shadow-sm border border-[#19140035] dark:border-[#3E3E3A]">
        <h3 class="text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">Recent Audit Logs</h3>
        @if ($recentAuditLogs->isEmpty())
            <p class="text-[#4a4a47] dark:text-[#A2A29D]">No recent activity logged.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-[#1b1b18] dark:text-[#EDEDEC] border-b border-[#19140035] dark:border-[#3E3E3A]">
                        <tr>
                            <th class="py-2 px-4">User</th>
                            <th class="py-2 px-4">Action</th>
                            <th class="py-2 px-4">Details</th>
                            <th class="py-2 px-4">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentAuditLogs as $log)
                            <tr class="border-b border-[#19140035] dark:border-[#3E3E3A] hover:bg-amber-50 dark:hover:bg-amber-900/30">
                                <td class="py-2 px-4">{{ $log->user->name }}</td>
                                <td class="py-2 px-4">{{ $log->action }}</td>
                                <td class="py-2 px-4">{{ $log->details ?? 'N/A' }}</td>
                                <td class="py-2 px-4">{{ $log->logged_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
