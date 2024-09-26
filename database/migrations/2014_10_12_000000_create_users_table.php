<?php

use App\Enums\UserType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // User information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('phone')->nullable(); // Optional phone number

            // Image URL for profile picture
            $table->string('image_url')->nullable(); // To store image URL, nullable if not mandatory

            // Authentication-related columns
            $table->string('password');
            $table->boolean('active')->default(true);
            $table->string('auth_provider')->nullable(); // Social login provider

            // Enum for user_type
            $table->enum('user_type', array_column(UserType::cases(), 'value')); // Enum for user type

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
