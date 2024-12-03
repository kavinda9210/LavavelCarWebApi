<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // 'id' column
            $table->string('nic')->nullable(); // 'nic' column, nullable
            $table->string('name'); // 'name' column
            $table->string('email')->unique(); // 'email' column, unique
            $table->timestamp('email_verified_at')->nullable(); // 'email_verified_at', nullable
            $table->string('address')->nullable(); // 'address' column, nullable
            $table->string('phone')->nullable(); // 'phone' column, nullable
            $table->string('password'); // 'password' column
            $table->enum('usertype', ['customer', 'admin'])->default('customer'); // 'usertype' column with default 'customer'
            $table->rememberToken(); // 'remember_token' column
            $table->timestamps(); // 'created_at' and 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users'); // Drop the 'users' table if rolling back
    }
}
