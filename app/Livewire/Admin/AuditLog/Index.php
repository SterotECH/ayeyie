<?php

namespace App\Livewire\Admin\AuditLog;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFilter = '';
    public $logLevelFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'dateFilter' => ['except' => ''],
        'logLevelFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function updatingLogLevelFilter()
    {
        $this->resetPage();
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
            ->when($this->dateFilter, function ($query) {
                return match ($this->dateFilter) {
                    'today' => $query->whereDate('logged_at', today()),
                    'week' => $query->whereBetween('logged_at', [now()->startOfWeek(), now()->endOfWeek()]),
                    'month' => $query->whereBetween('logged_at', [now()->startOfMonth(), now()->endOfMonth()]),
                    default => $query
                };
            })
            ->when($this->logLevelFilter, function ($query) {
                return $query->where('log_level', $this->logLevelFilter);
            })
            ->with('user')
            ->latest('logged_at')
            ->paginate(15);

        return view('livewire.admin.audit-log.index', [
            'logs' => $logs
        ]);
    }
}
