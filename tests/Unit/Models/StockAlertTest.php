<?php

declare(strict_types=1);

use App\Models\StockAlert;

it('can create a stock alert with different states', function () {
    $default = StockAlert::factory()->create();
    $critical = StockAlert::factory()->critical()->create();

    expect($default->current_quantity)->toBeLessThan(50)
        ->and($critical->current_quantity)->toBeLessThanOrEqual(10)
        ->and($critical->alert_message)->toBe('Critically low stock!');
});

it('has relationships', function () {
    $alert = StockAlert::factory()
        ->for(App\Models\Product::factory(), 'product')
        ->create();

    expect($alert->product)->not->toBeNull();
});
