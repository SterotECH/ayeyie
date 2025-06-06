<div class="py-6">
    <div class="">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-accent text-2xl font-bold">
                Suspicious Activity Details
            </h2>
            <div class="mt-4 md:mt-0">
                <a class="text-sm text-gray-600 hover:text-gray-900 dark:text-slate-50"
                    href="{{ route('admin.suspicious_activities.index') }}">
                    &larr; Back to All Activities
                </a>
            </div>
        </div>

        <div class="overflow-hidden bg-zinc-50 shadow sm:rounded-lg dark:bg-slate-900 dark:bg-zinc-800">
            <div class="flex items-center justify-between px-4 py-5 sm:px-6">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-slate-50">
                        Activity #{{ $activity->activity_id }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Detected on {{ $activity->detected_at->format('F j, Y \a\t g:i a') }}
                    </p>
                </div>
                <div>
                    {!! $activity->severity_badge !!}
                </div>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                        <dt class="text-sm font-medium text-gray-500">
                            User
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-slate-50">
                            {{ $activity->user->name ?? 'Unknown' }}
                            @if ($activity->user && $activity->user->email)
                                <div class="mt-1 text-xs text-gray-500">{{ $activity->user->email }}</div>
                            @endif
                        </dd>
                    </div>
                    <div
                        class="bg-zinc-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-slate-900 dark:bg-zinc-800">
                        <dt class="text-sm font-medium text-gray-500">
                            Entity Type
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-slate-50">
                            {{ $activity->entity_type }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                        <dt class="text-sm font-medium text-gray-500">
                            Entity ID
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-slate-50">
                            {{ $activity->entity_id }}
                        </dd>
                    </div>
                    <div
                        class="bg-zinc-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-slate-900 dark:bg-zinc-800">
                        <dt class="text-sm font-medium text-gray-500">
                            Description
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-slate-50">
                            {{ $activity->description }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-gray-700">
                        <dt class="text-sm font-medium text-gray-500">
                            Created At
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-slate-50">
                            {{ $activity->created_at->format('F j, Y \a\t g:i a') }}
                        </dd>
                    </div>
                    <div
                        class="bg-zinc-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 dark:bg-slate-900 dark:bg-zinc-800">
                        <dt class="text-sm font-medium text-gray-500">
                            Updated At
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-slate-50">
                            {{ $activity->updated_at->format('F j, Y \a\t g:i a') }}
                        </dd>
                    </div>
                </dl>
            </div>
            <div class="flex justify-between border-t border-gray-200 px-4 py-5 sm:px-6">
                <flux:button href="{{ route('admin.suspicious_activities.index') }}" variant="primary">
                    Back to List
                </flux:button>

                <!-- If you need action buttons, add them here -->
                <div>
                    <flux:button href="#">
                        Flag for Review
                    </flux:button>
                    <flux:button href="#" variant="filled">
                        Mark as Resolved
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</div>
