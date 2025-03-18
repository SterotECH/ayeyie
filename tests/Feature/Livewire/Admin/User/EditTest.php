<?php

use App\Livewire\Admin\User\Edit;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Edit::class)
        ->assertStatus(200);
});
