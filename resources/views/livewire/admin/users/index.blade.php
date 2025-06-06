<div class="overflow-hidden rounded-lg bg-zinc-50 shadow-xl dark:bg-gray-900 dark:bg-zinc-800">
    <div
        class="flex flex-col items-start justify-between gap-4 border-b border-gray-200 bg-gray-50 px-6 py-4 md:flex-row md:items-center dark:border-gray-700 dark:bg-gray-700 dark:bg-gray-800">
        <h2 class="flex items-center gap-2 text-xl font-semibold text-gray-800 dark:text-white">
            <svg class="h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            User Management
        </h2>
        <div class="flex items-center gap-3">
            <div class="relative">
                <input
                    class="rounded-lg border border-gray-300 px-4 py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    type="text" wire:model.debounce.300ms="searchTerm" placeholder="Search users..." />
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <flux:dropdown>
                <flux:button>
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                </flux:button>
                <flux:menu>
                    <div class="flex w-full flex-wrap items-end gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Filter by
                                role</label>
                            <select
                                class="block w-full rounded-lg border border-gray-300 bg-zinc-50 p-2.5 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:bg-zinc-800 dark:text-white"
                                wire:model.live.debounce="selectedRoleFilter">
                                <option value="">All roles</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </flux:menu>
            </flux:dropdown>
            <flux:dropdown>
                <flux:button>
                    <span>{{ $perPage }} per page</span>
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </flux:button>
                <flux:menu>
                    <div class="py-1">
                        <a class="{{ $perPage == 10 ? 'bg-gray-100 dark:bg-gray-700' : '' }} block cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            wire:click="$set('perPage', 10)">10 per page</a>
                        <a class="{{ $perPage == 25 ? 'bg-gray-100 dark:bg-gray-700' : '' }} block cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            wire:click="$set('perPage', 25)">25 per page</a>
                        <a class="{{ $perPage == 50 ? 'bg-gray-100 dark:bg-gray-700' : '' }} block cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            wire:click="$set('perPage', 50)">50 per page</a>
                        <a class="{{ $perPage == 100 ? 'bg-gray-100 dark:bg-gray-700' : '' }} block cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            wire:click="$set('perPage', 100)">100 per page</a>
                    </div>
                </flux:menu>
            </flux:dropdown>
            <flux:button href="{{ route('admin.users.create') }}" variant="primary">
                Add User
            </flux:button>

        </div>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:divide-gray-900">
            <thead class="bg-gray-50 dark:bg-gray-700 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400"
                        scope="col">
                        <div class="flex cursor-pointer items-center gap-1" wire:click="sortBy('name')">
                            Name
                            @if ($sortField === 'name')
                                <svg class="{{ $sortDirection === 'asc' ? '' : 'rotate-180' }} h-3 w-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400"
                        scope="col">
                        <div class="flex cursor-pointer items-center gap-1" wire:click="sortBy('email')">
                            Email
                            @if ($sortField === 'email')
                                <svg class="{{ $sortDirection === 'asc' ? '' : 'rotate-180' }} h-3 w-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400"
                        scope="col">
                        <div class="flex cursor-pointer items-center gap-1" wire:click="sortBy('role')">
                            Role
                            @if ($sortField === 'role')
                                <svg class="{{ $sortDirection === 'asc' ? '' : 'rotate-180' }} h-3 w-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400"
                        scope="col">
                        <div class="flex cursor-pointer items-center gap-1" wire:click="sortBy('created_at')">
                            Created At
                            @if ($sortField === 'created_at')
                                <svg class="{{ $sortDirection === 'asc' ? '' : 'rotate-180' }} h-3 w-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400"
                        scope="col">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody
                class="divide-y divide-gray-200 bg-zinc-50 dark:divide-gray-700 dark:divide-gray-900 dark:bg-gray-900 dark:bg-zinc-800">
                @forelse ($users as $user)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $user->name }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->email }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            <span
                                class="{{ $user->role === 'admin'
                                    ? 'bg-red-100 text-red-800'
                                    : ($user->role === 'manager'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-green-100 text-green-800') }} inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <flux:button href="{{ route('admin.users.show', $user) }}" variant="filled">
                                    <flux:icon.eye class="size-5" />
                                </flux:button>
                                <flux:button href="{{ route('admin.users.edit', $user) }}">
                                    <flux:icon.pencil-square class="size-5" />
                                </flux:button>
                                <flux:modal.trigger name="delete-user">
                                    <flux:button wire:click="confirmUserDeletion({{ $user->user_id }})"
                                        variant="danger">
                                        <flux:icon.trash class="size-5" />
                                    </flux:button>
                                </flux:modal.trigger>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400" colspan="5">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
        {{ $users->links() }}
    </div>

    <!-- Delete User Confirmation Modal -->
    <flux:modal class="md:w-96" name="delete-user">
        <div class="space-y-6">
            <flux:heading size="lg">Delete User</flux:heading>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Are you sure you want to delete this user? This action cannot be undone.
            </p>
            <div class="flex justify-end gap-3">
                <flux:button wire:click="deleteUser" variant="danger">
                    Delete User
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
