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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id')
                ->comment('Unique identifier for each product');
            $table->string('name', 100)
                ->comment('Product name, e.g., Premium Feed');
            $table->string('image')->nullable()
                ->comment('Optional product image');
            $table->text('description')
                ->nullable()
                ->comment('Optional product details');
            $table->decimal('price', 10, 2)
                ->comment('Current price per unit');
            $table->integer('stock_quantity')
                ->comment('Current stock level');
            $table->integer('threshold_quantity')
                ->default(50)
                ->comment('Minimum stock level for alerts');
            $table->timestamps();
        });
    }
};
