<?php

declare(strict_types=1);

use App\Models\Product;

it('can create a product with different stock levels', function () {
    $normal = Product::factory()->create();
    $lowStock = Product::factory()->lowStock()->create();

    expect($normal->stock_quantity)->toBeGreaterThanOrEqual(50)
        ->and($lowStock->stock_quantity)->toBeLessThan(50);
});

it('has relationships', function () {
    $product = Product::factory()
        ->has(App\Models\StockAlert::factory()->count(2), 'stockAlerts')
        ->has(App\Models\TransactionItem::factory()->count(3), 'transactionItems')
        ->create();

    expect($product->stockAlerts)->toHaveCount(2)
        ->and($product->transactionItems)->toHaveCount(3);
});
