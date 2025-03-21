<?php

namespace App\Livewire\Admin\SuspiciousActivity;

use App\Models\SuspiciousActivity;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public string $severity = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $search = '';
    public string $sortField = 'detected_at';
    public string $sortDirection = 'desc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSeverity(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters(): void
    {
        $this->severity = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->search = '';
        $this->resetPage();
    }

    public function render(): View
    {
        $query = SuspiciousActivity::query()->with(['user']);

        if ($this->severity) {
            $query->where('severity', $this->severity);
        }

        if ($this->dateFrom) {
            $query->where('detected_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->where('detected_at', '<=', $this->dateTo . ' 23:59:59');
        }

        if ($this->search) {
            $search = '%' . $this->search . '%';
            $query->where(function (Builder $q) use ($search) {
                $q->whereLike('description',  $search)
                    ->orWhereHas('user', function (Builder $u) use ($search) {
                        $u->whereLike('name', $search)
                            ->orWhereLike('email',  $search);
                    });
            });
        }

        $activities = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);
        return view('livewire.admin.suspicious-activity.index', [
            'activities' => $activities
        ]);
    }
}
