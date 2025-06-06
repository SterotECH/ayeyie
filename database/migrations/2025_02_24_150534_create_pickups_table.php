<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pickups', function (Blueprint $table) {
            $table->id('pickup_id')
                ->comment('Unique identifier for each pickup');
            $table->foreignId('receipt_id')
                ->constrained('receipts', 'receipt_id')
                ->unique()
                ->comment('Linked receipt');
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users', 'user_id')
                ->comment('Staff who processed pickup');
            $table->enum('pickup_status', ['pending', 'completed'])
                ->comment('Pickup state');
            $table->timestamp('pickup_date')
                ->nullable()
                ->comment('When pickup occurred');
            $table->boolean('is_synced')
                ->default(false)
                ->comment('Sync status for offline mode');
            $table->timestamps();
        });
    }
};
