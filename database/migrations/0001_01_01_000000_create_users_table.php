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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id')
                ->comment('Unique identifier for all users (staff, admin, customers)');
            $table->string('name', 100)
                ->comment('Full name of the user');
            $table->string('phone', 20)
                ->unique()
                ->comment('Phone number for contact or login');
            $table->string('email', 100)
                ->unique()
                ->nullable()
                ->comment('Email for login or notifications, optional for walk-ins');
            $table->string('password', 255)
                ->nullable()
                ->comment('Hashed password, null for unregistered walk-ins');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->enum('role', ['staff', 'admin', 'customer'])
                ->comment('User role: staff process transactions, admins manage, customers order');
            $table->string('language', 5)
                ->default('en')
                ->comment('Preferred language, e.g., en for English, tw for Twi');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }
};
