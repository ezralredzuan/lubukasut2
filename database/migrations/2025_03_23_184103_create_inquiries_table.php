<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('email', 255);
            $table->string('inquiries_title', 255);
            $table->text('description');
            $table->timestamps(); // Includes created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
