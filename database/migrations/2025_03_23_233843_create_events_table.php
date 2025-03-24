<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('title');
            $table->string('status');
            $table->unsignedBigInteger('staff_id');
            $table->timestamps();

            $table->foreign('staff_id')
                  ->references('id') // Ensure this matches the primary key in the staff table
                  ->on('staff')
                  ->onUpdate('no action')
                  ->onDelete('no action');
        });
        Schema::table('events', function (Blueprint $table) {
            $table->longText('content')->nullable(); // Stores the HTML content from GrapesJS
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
