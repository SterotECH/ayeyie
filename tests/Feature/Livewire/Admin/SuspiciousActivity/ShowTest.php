<?php

use App\Livewire\Admin\SuspiciousActivity\Show;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Show::class)
        ->assertStatus(200);
});
