<x-ui.admin-page-layout
    title="User Management"
    description="Manage system users and their roles"
    :breadcrumbs="[['label' => 'Users']]"
    :stats="[
        ['label' => 'Total Users', 'value' => number_format($stats['total']), 'icon' => 'users', 'iconBg' => 'bg-primary/10', 'iconColor' => 'text-primary'],
        ['label' => 'Administrators', 'value' => number_format($stats['admin']), 'icon' => 'shield-check', 'iconBg' => 'bg-error/10', 'iconColor' => 'text-error'],
        ['label' => 'Managers', 'value' => number_format($stats['manager']), 'icon' => 'user-group', 'iconBg' => 'bg-warning/10', 'iconColor' => 'text-warning'],
        ['label' => 'Regular Users', 'value' => number_format($stats['user']), 'icon' => 'user', 'iconBg' => 'bg-success/10', 'iconColor' => 'text-success']
    ]"
    :show-filters="true"
    search-placeholder="Search by name or email..."
    :has-active-filters="$search || array_filter($filters)"
>
    <x-slot:actions>
        <flux:button href="{{ route('admin.users.create') }}" variant="primary" icon="plus">
            Add User
        </flux:button>
    </x-slot:actions>

    <x-slot:filterSlot>
        <!-- Role Filter -->
        <div>
            <flux:field>
                <flux:label>Role</flux:label>
                <flux:select wire:model.live="filters.role" placeholder="All Roles">
                    <flux:select.option value="admin">Administrator</flux:select.option>
                    <flux:select.option value="manager">Manager</flux:select.option>
                    <flux:select.option value="user">User</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Per Page -->
        <div class="flex items-end">
            <flux:field>
                <flux:label>Per Page</flux:label>
                <flux:select wire:model.live="perPage">
                    <flux:select.option value="15">15</flux:select.option>
                    <flux:select.option value="25">25</flux:select.option>
                    <flux:select.option value="50">50</flux:select.option>
                    <flux:select.option value="100">100</flux:select.option>
                </flux:select>
            </flux:field>
        </div>
    </x-slot:filterSlot>

    <!-- Main Table -->
    <x-ui.admin-table 
        :headers="[
            ['label' => 'User', 'field' => 'name', 'sortable' => true],
            ['label' => 'Email', 'field' => 'email', 'sortable' => true],
            ['label' => 'Role', 'field' => 'role', 'sortable' => true],
            ['label' => 'Joined', 'field' => 'created_at', 'sortable' => true],
            ['label' => 'Actions']
        ]"
        :items="$users"
        empty-title="No Users Found"
        empty-description="No users match your current filters"
        :has-active-filters="$search || array_filter($filters)"
        :sort-by="$sortBy"
        :sort-direction="$sortDirection"
    >
        @foreach($users as $item)
            <tr class="hover:bg-muted transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-sm font-medium text-primary">
                                    {{ strtoupper(substr($item->name, 0, 2)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-text-primary">
                                {{ $item->name }}
                            </div>
                            <div class="text-sm text-text-secondary">
                                ID: {{ $item->user_id }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-text-primary">{{ $item->email }}</div>
                    @if($item->email_verified_at)
                        <div class="flex items-center text-xs text-success mt-1">
                            <flux:icon.check-circle class="w-3 h-3 mr-1" />
                            Verified
                        </div>
                    @else
                        <div class="flex items-center text-xs text-warning mt-1">
                            <flux:icon.clock class="w-3 h-3 mr-1" />
                            Pending
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($item->role === 'admin')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error/10 text-error">
                            <flux:icon.shield-check class="w-3 h-3 mr-1" />
                            Administrator
                        </span>
                    @elseif($item->role === 'manager')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning/10 text-warning">
                            <flux:icon.user-group class="w-3 h-3 mr-1" />
                            Manager
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success/10 text-success">
                            <flux:icon.user class="w-3 h-3 mr-1" />
                            User
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                    <div>{{ $item->created_at->format('M j, Y') }}</div>
                    <div class="text-xs">{{ $item->created_at->diffForHumans() }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        <flux:button href="{{ route('admin.users.show', $item) }}" variant="ghost" size="sm" icon="eye" title="View User" />
                        <flux:button href="{{ route('admin.users.edit', $item) }}" variant="ghost" size="sm" icon="pencil" title="Edit User" />
                        <flux:modal.trigger name="delete-user">
                            <flux:button wire:click="confirmUserDeletion({{ $item->user_id }})" variant="danger" size="sm" icon="trash" title="Delete User" />
                        </flux:modal.trigger>
                    </div>
                </td>
            </tr>
        @endforeach

        {{-- Empty Action Slot --}}
        <x-slot:emptyAction>
            <flux:button href="{{ route('admin.users.create') }}" variant="primary" icon="plus">
                Add First User
            </flux:button>
        </x-slot:emptyAction>
    </x-ui.admin-table>

    <!-- Pagination -->
    <x-ui.admin-pagination 
        :items="$users" 
        item-name="users"
        :has-active-filters="$search || array_filter($filters)"
    />

    <!-- Delete User Confirmation Modal -->
    <flux:modal name="delete-user" class="md:w-96">
        <div class="space-y-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-error/10 rounded-lg flex items-center justify-center">
                        <flux:icon.exclamation-triangle class="w-5 h-5 text-error" />
                    </div>
                </div>
                <div class="ml-3">
                    <flux:heading size="lg">Delete User</flux:heading>
                </div>
            </div>
            
            <p class="text-sm text-text-secondary">
                Are you sure you want to delete this user? This action cannot be undone and will permanently remove all user data.
            </p>
            
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-border">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="deleteUser" variant="danger" icon="trash">
                    Delete User
                </flux:button>
            </div>
        </div>
    </flux:modal>
</x-ui.admin-page-layout>