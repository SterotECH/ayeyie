<?php

namespace App\Livewire\Admin\AuditLog;

use App\Models\AuditLog;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Show extends Component
{
    public AuditLog $auditLog;

    public function mount(AuditLog $auditLog): void
    {
        $this->auditLog = $auditLog;
    }

    public function render(): View
    {
        return view('livewire.admin.audit-log.show');
    }
}
