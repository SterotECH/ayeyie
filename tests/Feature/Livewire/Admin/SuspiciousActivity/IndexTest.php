<?php

use App\Livewire\Admin\SuspiciousActivity\Index;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Index::class)
        ->assertStatus(200);
});
