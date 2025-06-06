<?php

namespace App\Livewire\Customer\Pickup;

use App\Models\Pickup;
use App\Models\Transaction;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Properties
    public $search = '';
    public $status = '';
    public $sortField = 'pickup_date';
    public $sortDirection = 'desc';

    // For details modal
    public $showDetailsModal = false;
    public $selectedPickup = null;
    public $pickupTransaction = null;

    // Lifecycle methods
    public function mount()
    {
        // Initialize component
    }

    #[Computed]
    public function pickups()
    {
        return Pickup::query()
            ->whereHas('receipt.transaction.customer', function (Builder $query): void {
                $query->where('user_id', Auth::id());
            })
            ->when($this->search, function ($query) {
                $query->whereHas('receipt', function ($q) {
                    $q->where('receipt_id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('pickup_status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->with(['receipt', 'user'])
            ->paginate(10);
    }

    // Sorting method
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Reset pagination when filters change
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    // View pickup details
    public function viewDetails(Pickup $pickup)
    {
        $this->selectedPickup = $pickup->load(['receipt']);

        // Load associated transaction
        $this->pickupTransaction = Transaction::query()
            ->whereHas('receipt', function ($query) use ($pickup) {
                $query->where('receipt_id', $pickup->receipt_id);
            })
            ->with(['staff', 'customer'])
            ->first();

        $this->showDetailsModal = true;
    }

    // Close modal
    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedPickup = null;
        $this->pickupTransaction = null;
    }

    public function render()
    {
        return view('livewire.customer.pickup.index');
    }
}
