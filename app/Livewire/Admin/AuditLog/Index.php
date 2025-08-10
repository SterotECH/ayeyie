<?php

namespace App\Livewire\Admin\AuditLog;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public array $filters = [
        'dateFilter' => '',
        'logLevelFilter' => ''
    ];
    public string $sortBy = 'logged_at';
    public string $sortDirection = 'desc';
    public int $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'filters' => ['except' => []],
        'perPage' => ['except' => 15],
    ];

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

    private function getStats(): array
    {
        $total = AuditLog::count();
        $today = AuditLog::whereDate('logged_at', today())->count();
        $critical = AuditLog::where('log_level', 'critical')->count();
        $errors = AuditLog::where('log_level', 'error')->count();

        return [
            'total' => $total,
            'today' => $today,
            'critical' => $critical,
            'errors' => $errors
        ];
    }

    public function render()
    {
        $logs = AuditLog::query()
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('action', 'like', '%' . $this->search . '%')
                        ->orWhere('details', 'like', '%' . $this->search . '%')
                        ->orWhere('entity_type', 'like', '%' . $this->search . '%')
                        ->orWhere('entity_id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filters['dateFilter'], function ($query) {
                return match ($this->filters['dateFilter']) {
                    'today' => $query->whereDate('logged_at', today()),
                    'week' => $query->whereBetween('logged_at', [now()->startOfWeek(), now()->endOfWeek()]),
                    'month' => $query->whereBetween('logged_at', [now()->startOfMonth(), now()->endOfMonth()]),
                    default => $query
                };
            })
            ->when($this->filters['logLevelFilter'], function ($query) {
                return $query->where('log_level', $this->filters['logLevelFilter']);
            })
            ->with('user')
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.audit-log.index', [
            'logs' => $logs,
            'stats' => $this->getStats()
        ]);
    }
}
