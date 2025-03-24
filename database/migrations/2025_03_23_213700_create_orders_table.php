<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('reference_no');
            $table->string('no_phone');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->date('order_date');
            $table->integer('payment_status')->default(0);
            $table->integer('delivery_status')->default(0);
            $table->date('shipped_date')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('no action');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
