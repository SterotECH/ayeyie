<?php

declare(strict_types=1);

use App\Models\Receipt;

it('can create a receipt with different states', function () {
    $synced = Receipt::factory()->create();
    $unsynced = Receipt::factory()->unsynced()->create();

    expect($synced->is_synced)->toBeTrue()
        ->and($unsynced->is_synced)->toBeFalse();
});

it('has relationships', function () {
    $receipt = Receipt::factory()
        ->for(App\Models\Transaction::factory(), 'transaction')
        ->has(App\Models\Pickup::factory(), 'pickup')
        ->create();

    expect($receipt->transaction)->not->toBeNull()
        ->and($receipt->pickup)->not->toBeNull();
});
