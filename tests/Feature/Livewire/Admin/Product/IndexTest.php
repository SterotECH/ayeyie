<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Livewire::test('admin.product.index');

    $component->assertSee('');
});
