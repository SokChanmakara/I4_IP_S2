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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('customer_id')->unsigned();
            $table->timestamp('payment_date');
            $table->string('payment_method',100);
            $table->decimal('amount',10,2);
            $table->foreign('order_id')->references('orders')->on('id')->onDelete('cascade');
            $table->foreign('customer_id')->references('customers')->on('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
