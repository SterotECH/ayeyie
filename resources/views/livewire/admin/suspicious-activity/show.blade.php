<x-ui.admin-page-layout 
    title="Suspicious Activity #{{ $suspiciousActivity->activity_id }}"
    description="Detailed view of suspicious activity and related information"
    :breadcrumbs="[
        ['label' => 'Suspicious Activities', 'url' => route('admin.suspicious_activities.index')],
        ['label' => 'Activity #' . $suspiciousActivity->activity_id]
    ]"
    :show-filters="false"
>
    <x-slot:actions>
        <flux:button href="{{ route('admin.suspicious_activities.index') }}" variant="ghost" icon="arrow-left">
            Back to Activities
        </flux:button>
        <flux:button href="#" variant="primary" icon="flag">
            Flag for Review
        </flux:button>
        <flux:button href="#" variant="success" icon="check">
            Mark as Resolved
        </flux:button>
    </x-slot:actions>

    <!-- Activity Details Card -->
    <div class="bg-card rounded-lg border border-border shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-border">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-text-primary">Activity Details</h3>
                    <p class="text-sm text-text-secondary mt-1">
                        Detected on {{ $suspiciousActivity->detected_at->format('M j, Y \a\t g:i A') }}
                    </p>
                </div>
                <div>
                    @if($suspiciousActivity->severity === 'high')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-error/10 text-error">
                            <flux:icon.exclamation-triangle class="w-4 h-4 mr-1" />
                            High Risk
                        </span>
                    @elseif($suspiciousActivity->severity === 'medium')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-warning/10 text-warning">
                            <flux:icon.exclamation-circle class="w-4 h-4 mr-1" />
                            Medium Risk
                        </span>
                    @elseif($suspiciousActivity->severity === 'low')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-success/10 text-success">
                            <flux:icon.information-circle class="w-4 h-4 mr-1" />
                            Low Risk
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-muted text-text-secondary">
                            <flux:icon.question-mark-circle class="w-4 h-4 mr-1" />
                            Unknown
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- User Information -->
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-text-secondary">Associated User</dt>
                    <dd class="mt-2">
                        @if($suspiciousActivity->user)
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                        <span class="text-sm font-medium text-primary">
                                            {{ strtoupper(substr($suspiciousActivity->user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-text-primary">{{ $suspiciousActivity->user->name }}</div>
                                    @if($suspiciousActivity->user->email)
                                        <div class="text-sm text-text-secondary">{{ $suspiciousActivity->user->email }}</div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-sm text-text-secondary">Unknown User</div>
                        @endif
                    </dd>
                </div>

                <!-- Entity Type -->
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Entity Type</dt>
                    <dd class="mt-1 text-sm text-text-primary font-medium">{{ $suspiciousActivity->entity_type ?? 'N/A' }}</dd>
                </div>

                <!-- Entity ID -->
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Entity ID</dt>
                    <dd class="mt-1 text-sm text-text-primary font-medium">{{ $suspiciousActivity->entity_id ?? 'N/A' }}</dd>
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-text-secondary">Description</dt>
                    <dd class="mt-1 text-sm text-text-primary">{{ $suspiciousActivity->description }}</dd>
                </div>

                <!-- Detection Time -->
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Detected At</dt>
                    <dd class="mt-1 text-sm text-text-primary">
                        {{ $suspiciousActivity->detected_at->format('M j, Y g:i A') }}
                    </dd>
                </div>

                <!-- Risk Level -->
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Risk Level</dt>
                    <dd class="mt-1">
                        @if($suspiciousActivity->severity === 'high')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                                <flux:icon.exclamation-triangle class="w-3 h-3 mr-1" />
                                High Risk
                            </span>
                        @elseif($suspiciousActivity->severity === 'medium')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                                <flux:icon.exclamation-circle class="w-3 h-3 mr-1" />
                                Medium Risk
                            </span>
                        @elseif($suspiciousActivity->severity === 'low')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                                <flux:icon.information-circle class="w-3 h-3 mr-1" />
                                Low Risk
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-muted text-text-secondary">
                                <flux:icon.question-mark-circle class="w-3 h-3 mr-1" />
                                Unknown
                            </span>
                        @endif
                    </dd>
                </div>

                <!-- Created At -->
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Record Created</dt>
                    <dd class="mt-1 text-sm text-text-primary">{{ $suspiciousActivity->created_at->format('M j, Y g:i A') }}</dd>
                </div>

                <!-- Updated At -->
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-text-secondary">Last Updated</dt>
                    <dd class="mt-1 text-sm text-text-primary">{{ $suspiciousActivity->updated_at->format('M j, Y g:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @if($suspiciousActivity->user)
        <!-- Related User Activities -->
        <div class="mt-6 bg-card rounded-lg border border-border shadow-sm">
            <div class="px-6 py-4 border-b border-border">
                <h3 class="text-lg font-semibold text-text-primary">User Activity Summary</h3>
                <p class="text-sm text-text-secondary mt-1">Recent activities from this user account</p>
            </div>
            <div class="px-6 py-4">
                <div class="text-center py-8">
                    <flux:icon.clock class="w-8 h-8 text-text-secondary mx-auto mb-2" />
                    <p class="text-text-secondary text-sm">User activity details can be implemented here</p>
                    <flux:button href="{{ route('admin.users.show', $suspiciousActivity->user) }}" variant="ghost" class="mt-3" icon="user">
                        View User Profile
                    </flux:button>
                </div>
            </div>
        </div>
    @endif
</x-ui.admin-page-layout>
