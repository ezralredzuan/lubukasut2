<?php

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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable()->unique();
            $table->string('password');
            $table->string('staff_pic')->nullable(); // Stores file path, not the actual image
            $table->string('staff_name');
            $table->text('address');
            $table->string('no_phone');
            $table->string('email')->nullable()->unique();
            $table->date('hired_date');
            $table->string('role')->default('Staff'); // Default role
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
