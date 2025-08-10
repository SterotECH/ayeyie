<?php

declare(strict_types=1);

namespace App\Livewire\Admin\SuspiciousActivity;

use App\Models\SuspiciousActivity;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public array $filters = [
        'severity' => '',
        'dateFrom' => '',
        'dateTo' => '',
    ];

    public string $sortBy = 'detected_at';

    public string $sortDirection = 'desc';

    public int $perPage = 15;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilters(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters(): void
    {
        $this->filters = [
            'severity' => '',
            'dateFrom' => '',
            'dateTo' => '',
        ];
        $this->search = '';
        $this->resetPage();
    }

    public function render(): View
    {
        $query = SuspiciousActivity::query()->with(['user']);

        if ($this->filters['severity']) {
            $query->where('severity', $this->filters['severity']);
        }

        if ($this->filters['dateFrom']) {
            $query->where('detected_at', '>=', $this->filters['dateFrom']);
        }

        if ($this->filters['dateTo']) {
            $query->where('detected_at', '<=', $this->filters['dateTo'] . ' 23:59:59');
        }

        if ($this->search) {
            $search = '%' . $this->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', $search)
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', $search)
                            ->orWhere('email', 'like', $search);
                    });
            });
        }

        $activities = $query->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.suspicious-activity.index', [
            'activities' => $activities,
            'stats' => $this->getStats(),
        ]);
    }

    private function getStats(): array
    {
        $total = SuspiciousActivity::count();
        $high = SuspiciousActivity::where('severity', 'high')->count();
        $medium = SuspiciousActivity::where('severity', 'medium')->count();
        $low = SuspiciousActivity::where('severity', 'low')->count();

        return [
            'total' => $total,
            'high' => $high,
            'medium' => $medium,
            'low' => $low,
        ];
    }
}
