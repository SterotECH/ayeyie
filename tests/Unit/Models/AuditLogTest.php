<?php

declare(strict_types=1);

use App\Models\AuditLog;

it('can create an audit log with different actions', function () {
    $default = AuditLog::factory()->create();
    $payment = AuditLog::factory()->payment()->create();

    expect($default->action)->toBeIn(['login', 'payment_processed', 'pickup_verified'])
        ->and($payment->action)->toBe('payment_processed')
        ->and($payment->details)->toContain('$');
});

it('has relationships', function () {
    $log = AuditLog::factory()
        ->for(App\Models\User::factory(), 'user')
        ->for(App\Models\Transaction::factory(), 'entity')
        ->create();

    expect($log->user)->not->toBeNull()
        ->and($log->entity)->not->toBeInstanceOf(App\Models\Transaction::class);
});
