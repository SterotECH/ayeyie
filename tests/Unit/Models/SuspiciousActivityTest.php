<?php

declare(strict_types=1);

use App\Models\SuspiciousActivity;

it('can create a suspicious activity with different severities', function () {
    $medium = SuspiciousActivity::factory()->create();
    $low = SuspiciousActivity::factory()->low()->create();
    $high = SuspiciousActivity::factory()->high()->create();

    expect($medium->severity)->toBe('medium')
        ->and($low->severity)->toBe('low')
        ->and($high->severity)->toBe('high');
});

it('has relationships', function () {
    $activity = SuspiciousActivity::factory()
        ->for(App\Models\User::factory(), 'user')
        ->for(App\Models\User::factory(), 'entity')
        ->create();

    expect($activity->user)->not->toBeNull()
        ->and($activity->entity)->not->toBeNull();
});
