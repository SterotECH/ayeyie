<?php

declare(strict_types=1);

use App\Models\Pickup;

it('can create a pickup with different states', function () {
    $completed = Pickup::factory()->create();
    $pending = Pickup::factory()->pending()->create();

    expect($completed->pickup_status)->toBe('completed')
        ->and($completed->pickup_date)->not->toBeNull()
        ->and($completed->is_synced)->toBeTrue()
        ->and($pending->pickup_status)->toBe('pending')
        ->and($pending->pickup_date)->toBeNull()
        ->and($pending->is_synced)->toBeFalse();
});

it('has relationships', function () {
    $pickup = Pickup::factory()
        ->for(App\Models\Receipt::factory(), 'receipt')
        ->for(App\Models\User::factory()->staff(), 'user')
        ->create();

    expect($pickup->receipt)->not->toBeNull()
        ->and($pickup->user)->not->toBeNull();
});
