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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('log_id')
                ->comment('Unique identifier for audit log');
            $table->foreignId('user_id')
                ->constrained('users', 'user_id')
                ->comment('User who acted (staff, admin, customer)');
            $table->string('action', 50)
                ->comment('Action type, e.g., payment_processed');
            $table->morphs('entity');
            $table->text('details')
                ->nullable()
                ->comment('Additional info');
            $table->timestamp('logged_at')
                ->comment('When action occurred');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
