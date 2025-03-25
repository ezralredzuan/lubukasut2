<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('reference_no');
            $table->timestamps();

            $table->foreign('reference_no')
                ->references('reference_no')
                ->on('orders')
                ->onUpdate('cascade')
                ->onDelete('no action');

            $table->index('reference_no', 'FK_payments_orders');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
