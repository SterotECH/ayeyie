<?php

use App\Livewire\Admin\Users\Index;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Index::class)
        ->assertStatus(200);
});
