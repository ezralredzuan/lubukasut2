<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key (ProductID)
            $table->binary('product_image')->nullable(); // Store product images as BLOB
            $table->string('gender', 255);
            $table->string('name', 255);
            $table->string('size', 255);
            $table->decimal('price', 10, 2);
            $table->text('description');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade'); // Foreign key to brands
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade'); // Foreign key to staff
            $table->timestamps(); // Includes created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
