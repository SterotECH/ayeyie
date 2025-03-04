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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id')
                ->comment('Unique identifier for each transaction');
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->comment('Staff who processed the transaction');
            $table->foreignId('customer_user_id')
                ->nullable()
                ->constrained('users', 'user_id')
                ->comment('Customer who ordered, null for walk-ins');
            $table->decimal('total_amount', 10, 2)
                ->comment('Total payment amount');
            $table->enum('payment_status', ['pending', 'completed', 'failed'])
                ->comment('Payment state');
            $table->string('payment_method', 50)
                ->comment('Method, e.g., cash, card');
            $table->timestamp('transaction_date')
                ->comment('When the transaction occurred');
            $table->boolean('is_synced')
                ->default(false)
                ->comment('Sync status for offline mode');
            $table->timestamps();
        });
    }
};
