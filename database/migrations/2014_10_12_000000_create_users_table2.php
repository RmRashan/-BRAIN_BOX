<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new columns for first name, last name, phone, and image URL
            $table->string('first_name')->after('id'); // Adds first_name column after the id
            $table->string('last_name')->after('first_name'); // Adds last_name column after first_name
            $table->string('phone')->nullable()->after('last_name'); // Adds phone column after last_name
            $table->string('image_url')->nullable()->after('email'); // Adds image_url column after email
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the columns on rollback
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('phone');
            $table->dropColumn('image_url');
        });
    }
};
