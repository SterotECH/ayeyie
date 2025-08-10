<x-ui.admin-page-layout
    title="Audit Log Details"
    description="Detailed view of system activity and user action log"
    :breadcrumbs="[
        ['label' => 'Audit Logs', 'url' => route('admin.audit_logs.index')],
        ['label' => 'Log #' . $auditLog->log_id]
    ]"
    :show-filters="false"
>
    <x-slot:actions>
        <flux:button href="{{ route('admin.audit_logs.index') }}" variant="ghost" icon="arrow-left">
            Back to Logs
        </flux:button>
    </x-slot:actions>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Log Details Card -->
        <div class="lg:col-span-2">
            <div class="bg-card rounded-lg border border-border shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-text-primary">Audit Log Information</h3>
                            <p class="text-sm text-text-secondary mt-1">
                                @if($auditLog->logged_at)
                                    Logged on {{ $auditLog->logged_at->format('M j, Y g:i A') }}
                                @endif
                            </p>
                        </div>
                        <div>
                            @if($auditLog->log_level === 'critical')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-error/10 text-error">
                                    <flux:icon.exclamation-triangle class="w-4 h-4 mr-1"/>
                                    Critical
                                </span>
                            @elseif($auditLog->log_level === 'error')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-warning/10 text-warning">
                                    <flux:icon.x-circle class="w-4 h-4 mr-1"/>
                                    Error
                                </span>
                            @elseif($auditLog->log_level === 'warning')
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-warning/10 text-warning">
                                    <flux:icon.exclamation-circle class="w-4 h-4 mr-1"/>
                                    Warning
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-success/10 text-success">
                                    <flux:icon.information-circle class="w-4 h-4 mr-1"/>
                                    Info
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- User Information -->
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-text-secondary">Performed By</dt>
                            <dd class="mt-2">
                                @if($auditLog->user)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                                <span class="text-sm font-medium text-primary">
                                                    {{ strtoupper(substr($auditLog->user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div
                                                class="text-sm font-medium text-text-primary">{{ $auditLog->user->name }}</div>
                                            @if($auditLog->user->email)
                                                <div
                                                    class="text-sm text-text-secondary">{{ $auditLog->user->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                                <flux:icon.cog class="w-5 h-5 text-primary"/>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-text-primary">System</div>
                                            <div class="text-sm text-text-secondary">Automated action</div>
                                        </div>
                                    </div>
                                @endif
                            </dd>
                        </div>

                        <!-- Action -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-text-secondary">Action</dt>
                            <dd class="mt-1 text-sm font-semibold text-text-primary">{{ $auditLog->action }}</dd>
                        </div>

                        <!-- Entity -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-text-secondary">Target Entity</dt>
                            <dd class="mt-1">
                                <div
                                    class="text-sm font-medium text-text-primary">{{ class_basename($auditLog->entity_type) }}</div>
                                <div class="text-sm text-text-secondary">ID: {{ $auditLog->entity_id }}</div>
                            </dd>
                        </div>

                        <!-- Log Level -->
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-text-secondary">Log Level</dt>
                            <dd class="mt-1">
                                @if($auditLog->log_level === 'critical')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                                        <flux:icon.exclamation-triangle class="w-3 h-3 mr-1"/>
                                        Critical
                                    </span>
                                @elseif($auditLog->log_level === 'error')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                                        <flux:icon.x-circle class="w-3 h-3 mr-1"/>
                                        Error
                                    </span>
                                @elseif($auditLog->log_level === 'warning')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                                        <flux:icon.exclamation-circle class="w-3 h-3 mr-1"/>
                                        Warning
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                                        <flux:icon.information-circle class="w-3 h-3 mr-1"/>
                                        Info
                                    </span>
                                @endif
                            </dd>
                        </div>

                        <!-- Timestamp -->
                        @if($auditLog->logged_at)
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-text-secondary">Logged At</dt>
                                <dd class="mt-1">
                                    <div
                                        class="text-sm font-medium text-text-primary">{{ $auditLog->logged_at->format('M j, Y') }}</div>
                                    <div
                                        class="text-sm text-text-secondary">{{ $auditLog->logged_at->format('g:i:s A') }}</div>
                                </dd>
                            </div>
                        @endif

                        <!-- Details -->
                        @if($auditLog->details)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-text-secondary mb-3">Details</dt>
                                <dd>
                                    <x-audit.details-formatter :details="$auditLog->details"/>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <!-- Additional Information Panel -->
        <div class="lg:col-span-1">
            <div class="bg-card rounded-lg border border-border shadow-sm">
                <div class="px-6 py-4 border-b border-border">
                    <h3 class="text-lg font-semibold text-text-primary">Related Information</h3>
                    <p class="text-sm text-text-secondary mt-1">Context and related data</p>
                </div>
                <div class="px-6 py-6 space-y-4">
                    @if($auditLog->user)
                        <div class="text-center">
                            <flux:button href="{{ route('admin.users.show', $auditLog->user) }}" variant="ghost"
                                         size="sm" icon="user" class="w-full">
                                View User Profile
                            </flux:button>
                        </div>
                    @endif

                    <!-- Additional context can be added here -->
                    <div class="text-center py-4">
                        <flux:icon.information-circle class="w-8 h-8 text-text-secondary mx-auto mb-2"/>
                        <p class="text-text-secondary text-sm">Additional context and related logs can be displayed
                            here</p>
                    </div>
                </div>
            </div>

            <!-- Log Metadata -->
            <div class="mt-6 bg-card rounded-lg border border-border shadow-sm">
                <div class="px-6 py-4 border-b border-border">
                    <h3 class="text-lg font-semibold text-text-primary">Log Metadata</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-xs font-medium text-text-secondary uppercase tracking-wider">Log ID</dt>
                            <dd class="mt-1 text-sm font-mono text-text-primary">{{ $auditLog->log_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-text-secondary uppercase tracking-wider">Entity Type
                            </dt>
                            <dd class="mt-1 text-sm font-mono text-text-primary">{{ $auditLog->entity_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-text-secondary uppercase tracking-wider">Entity ID</dt>
                            <dd class="mt-1 text-sm font-mono text-text-primary">{{ $auditLog->entity_id }}</dd>
                        </div>
                        @if($auditLog->user_id)
                            <div>
                                <dt class="text-xs font-medium text-text-secondary uppercase tracking-wider">User ID
                                </dt>
                                <dd class="mt-1 text-sm font-mono text-text-primary">{{ $auditLog->user_id }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-ui.admin-page-layout>
