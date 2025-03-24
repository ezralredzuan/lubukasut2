<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id('promotion_id'); // Primary Key
            $table->string('name', 255);
            $table->string('promotion_code', 255);
            $table->integer('discount_percentage');
            $table->date('valid_until_date');
            $table->foreignId('staff_id')->constrained('staff')->onUpdate('cascade')->onDelete('no action');
            $table->timestamps(); // Includes created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
