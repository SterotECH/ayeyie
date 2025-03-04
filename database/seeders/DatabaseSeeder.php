<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(5)->staff()->create();
        \App\Models\User::factory(2)->admin()->create();
        \App\Models\User::factory(10)->create();
        \App\Models\User::factory(3)->walkIn()->create();

        \App\Models\Product::factory(10)->create();
        \App\Models\StockAlert::factory(5)->create();

        \App\Models\Transaction::factory(15)->create();
        \App\Models\TransactionItem::factory(30)->create();
        \App\Models\Receipt::factory(10)->create();
        \App\Models\Pickup::factory(8)->create();

        \App\Models\SuspiciousActivity::factory(5)->create();
        \App\Models\AuditLog::factory(10)->create();
    }
}
