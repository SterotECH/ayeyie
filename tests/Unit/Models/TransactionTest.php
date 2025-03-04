<?php

declare(strict_types=1);

use App\Models\Transaction;

it('can create a transaction with different states', function () {
    $completed = Transaction::factory()->create();
    $pending = Transaction::factory()->pending()->create();
    $failed = Transaction::factory()->failed()->create();
    $walkIn = Transaction::factory()->walkIn()->create();

    expect($completed->payment_status)->toBe('completed')
        ->and($completed->is_synced)->toBeTrue()
        ->and($pending->payment_status)->toBe('pending')
        ->and($pending->is_synced)->toBeFalse()
        ->and($failed->payment_status)->toBe('failed')
        ->and($walkIn->customer_user_id)->toBeNull();
});

it('has relationships', function () {
    $transaction = Transaction::factory()
        ->for(App\Models\User::factory()->staff(), 'user')
        ->has(App\Models\Receipt::factory(), 'receipt')
        ->has(App\Models\TransactionItem::factory()->count(2), 'items')
        ->create();

    expect($transaction->user)->not->toBeNull()
        ->and($transaction->receipt)->not->toBeNull()
        ->and($transaction->items)->toHaveCount(2);
});
