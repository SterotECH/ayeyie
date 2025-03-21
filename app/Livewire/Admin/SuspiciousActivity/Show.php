<?php

namespace App\Livewire\Admin\SuspiciousActivity;

use App\Models\SuspiciousActivity;
use Livewire\Component;

class Show extends Component
{
    public SuspiciousActivity $suspiciousActivity;

    public function mount(SuspiciousActivity $suspiciousActivity): void
    {
        $this->suspiciousActivity = $suspiciousActivity;
    }

    public function render()
    {
        return view('livewire.admin.suspicious-activity.show');
    }
}
