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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id('transaction_item_id')
                ->comment('Unique identifier for each item');
            $table->foreignId('transaction_id')
                ->constrained('transactions', 'transaction_id')
                ->comment('Parent transaction');
            $table->foreignId('product_id')
                ->constrained('products', 'product_id')
                ->comment('Product purchased');
            $table->integer('quantity')
                ->comment('Units bought');
            $table->decimal('unit_price', 10, 2)
                ->comment('Price per unit at purchase');
            $table->decimal('subtotal', 10, 2)
                ->comment('Quantity * unit_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
