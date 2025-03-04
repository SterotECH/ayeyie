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
        Schema::create('suspicious_activities', function (Blueprint $table) {
            $table->id('activity_id')
                ->comment('Unique identifier for suspicious event');
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->comment('Staff or customer involved');
            $table->morphs('entity');
            $table->text('description')
                ->comment('Details of suspicious action');
            $table->enum('severity', ['low', 'medium', 'high'])
                ->comment('Risk level');
            $table->timestamp('detected_at')
                ->comment('When flagged');
            $table->timestamps();
        });
    }
};
