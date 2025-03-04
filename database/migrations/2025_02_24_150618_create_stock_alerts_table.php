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
        Schema::create('stock_alerts', function (Blueprint $table) {
            $table->id('alert_id')
                ->comment('Unique identifier for stock alert');
            $table->foreignId('product_id')
                ->constrained('products', 'product_id')
                ->comment('Product nearing low stock');
            $table->integer('current_quantity')
                ->comment('Stock level when triggered');
            $table->integer('threshold')
                ->comment('Minimum stock level');
            $table->text('alert_message')
                ->comment('Notification text');
            $table->timestamp('triggered_at')
                ->comment('When alert was generated');
            $table->timestamps();
        });
    }
};
