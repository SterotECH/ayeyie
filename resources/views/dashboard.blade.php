<x-layouts.app>
    @if(auth()->user()->role === 'admin')
    <livewire:admin.dashboard />
    @elseif(auth()->user()->role === 'staff')
        <livewire:staff.dashboard />
    @elseif(auth()->user()->role === 'customer')
        <livewire:customer.dashboard />
    @else
    // no role assign display generic message
        <div class="text-center p-6">
            <h1 class="text-2xl font-bold text-text-primary">Welcome to the Dashboard</h1>
            <p class="mt-4 text-text-secondary">Please contact your administrator for access.</p>
        </div>
    @endif
</x-layouts.app>
