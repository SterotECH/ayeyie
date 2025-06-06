<?php

use App\Livewire\DataTable;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(DataTable::class)
        ->assertStatus(200);
});
