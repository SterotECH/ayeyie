<?php

use App\Livewire\Admin\User\Create;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Create::class)
        ->assertStatus(200);
});
