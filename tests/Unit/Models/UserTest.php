<?php

declare(strict_types=1);

use App\Models\User;

it('can create a user with different roles', function () {
    $staff = User::factory()->staff()->create();
    $admin = User::factory()->admin()->create();
    $customer = User::factory()->create();
    $walkIn = User::factory()->walkIn()->create();

    expect($staff->role)->toBe('staff')
        ->and($admin->role)->toBe('admin')
        ->and($customer->role)->toBe('customer')
        ->and($walkIn->email)->toBeNull()
        ->and($walkIn->password)->toBeNull();
});

it('can generate user initials', function () {
    $user = User::factory()->create(['name' => 'John Doe']);
    expect($user->initials())->toBe('JD');
});

it('has relationships', function () {
    $user = User::factory()
        ->hasTransactionsAsStaff(2)
        ->hasTransactionsAsCustomer(1)
        ->hasPickups(3)
        ->hasSuspiciousActivitiesAsUser(1)
        ->hasSuspiciousActivities(2)
        ->hasAuditLogs(1)
        ->create();

    expect($user->transactionsAsStaff)->toHaveCount(2)
        ->and($user->transactionsAsCustomer)->toHaveCount(1)
        ->and($user->pickups)->toHaveCount(3)
        ->and($user->suspiciousActivitiesAsUser)->toHaveCount(1)
        ->and($user->suspiciousActivities)->toHaveCount(2)
        ->and($user->auditLogs)->toHaveCount(1);
});
