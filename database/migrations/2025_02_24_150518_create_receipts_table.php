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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id('receipt_id')
                ->comment('Unique identifier for each receipt');
            $table->foreignId('transaction_id')
                ->constrained('transactions', 'transaction_id')
                ->unique()
                ->comment('Linked transaction');
            $table->string('receipt_code', 20)
                ->unique()
                ->comment('Human-readable receipt code');
            $table->string('qr_code', 255)
                ->comment('QR code for pickup verification');
            $table->timestamp('issued_at')
                ->comment('When receipt was issued');
            $table->boolean('is_synced')
                ->default(false)
                ->comment('Sync status for offline mode');
            $table->timestamps();
        });
    }
};
