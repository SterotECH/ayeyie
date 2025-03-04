<?php

declare(strict_types=1);

use App\Models\TransactionItem;

it('can create a transaction item with different quantities', function () {
    $default = TransactionItem::factory()->create();
    $highQty = TransactionItem::factory()->highQuantity()->create();

    expect($default->quantity)->toBeLessThanOrEqual(10)
        ->and($highQty->quantity)->toBeGreaterThanOrEqual(20);
});

it('has relationships', function () {
    $item = TransactionItem::factory()
        ->for(App\Models\Transaction::factory(), 'transaction')
        ->for(App\Models\Product::factory(), 'product')
        ->create();

    expect($item->transaction)->not->toBeNull()
        ->and($item->product)->not->toBeNull();
});
