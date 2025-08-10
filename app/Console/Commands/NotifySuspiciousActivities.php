<?php

namespace App\Console\Commands;

use App\Models\SuspiciousActivity;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class NotifySuspiciousActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suspicious:notify {--email : Send email notifications (requires mail configuration)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and notify administrators about recent suspicious activities';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sendEmail = $this->option('email');
        $yesterday = CarbonImmutable::now()->subDay();
        
        // Get suspicious activities from the last 24 hours
        $recentActivities = SuspiciousActivity::with('user')
            ->where('created_at', '>=', $yesterday)
            ->where('is_resolved', false)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($recentActivities->isEmpty()) {
            $this->info('No new suspicious activities found in the last 24 hours.');
            return Command::SUCCESS;
        }

        $this->warn("Found {$recentActivities->count()} new suspicious activities in the last 24 hours:");
        $this->newLine();

        // Group by activity type for better reporting
        $groupedActivities = $recentActivities->groupBy('activity_type');
        
        foreach ($groupedActivities as $activityType => $activities) {
            $this->line("🚨 <fg=red>{$activityType}</> ({$activities->count()} incidents):");
            
            foreach ($activities as $activity) {
                $customerInfo = $activity->user ? 
                    "{$activity->user->name} ({$activity->user->email})" : 
                    'Unknown User';
                    
                $this->line("  - {$customerInfo} - {$activity->description}");
                $this->line("    <fg=gray>Severity: {$activity->severity} | Time: {$activity->created_at->format('Y-m-d H:i:s')}</>");
                
                if ($activity->metadata) {
                    $metadata = json_decode($activity->metadata, true);
                    if (isset($metadata['unpaid_orders_count'])) {
                        $this->line("    <fg=gray>Unpaid Orders: {$metadata['unpaid_orders_count']}</>");
                    }
                }
                $this->newLine();
            }
        }

        // Generate summary report
        $this->generateSummaryReport($groupedActivities);

        // Send email notifications if requested
        if ($sendEmail) {
            $this->sendEmailNotifications($recentActivities);
        }

        return Command::SUCCESS;
    }

    private function generateSummaryReport($groupedActivities): void
    {
        $this->info('📊 Summary Report:');
        $this->table(
            ['Activity Type', 'Count', 'Severity Distribution'],
            $groupedActivities->map(function ($activities, $type) {
                $severityCount = $activities->groupBy('severity')->map(function ($items) {
                    return $items->count();
                })->toArray();
                
                $severityText = collect($severityCount)->map(function ($count, $severity) {
                    return "{$severity}: {$count}";
                })->implode(', ');
                
                return [
                    $type,
                    $activities->count(),
                    $severityText
                ];
            })
        );

        // Get administrators to notify
        $admins = User::where('role', 'admin')->get();
        $this->info("📧 {$admins->count()} administrators will be notified.");
    }

    private function sendEmailNotifications($activities): void
    {
        // Get all administrators
        $admins = User::where('role', 'admin')->get();
        
        if ($admins->isEmpty()) {
            $this->warn('No administrators found to notify.');
            return;
        }

        $this->info('Sending email notifications to administrators...');
        
        // This would typically use Laravel's Mail facade
        // For now, we'll log the notification
        foreach ($admins as $admin) {
            logger()->info('Suspicious activity notification', [
                'admin_email' => $admin->email,
                'admin_name' => $admin->name,
                'activities_count' => $activities->count(),
                'activities' => $activities->map(function ($activity) {
                    return [
                        'type' => $activity->activity_type,
                        'description' => $activity->description,
                        'user' => $activity->user?->name ?? 'Unknown',
                        'severity' => $activity->severity,
                        'created_at' => $activity->created_at->toISOString()
                    ];
                })
            ]);
            
            $this->line("📧 Notification logged for {$admin->name} ({$admin->email})");
        }
        
        $this->info('✅ Email notifications processed.');
        $this->comment('Note: Actual email sending requires proper mail configuration.');
        $this->comment('Check the logs for notification details that can be used to implement actual email sending.');
    }
}