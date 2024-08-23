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
        Schema::create('mpesa_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('MerchantRequestID')->nullable();
            $table->string('CheckoutRequestID')->nullable();
            $table->string('transaction_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->date('transaction_date')->nullable();
            $table->decimal('transaction_amount', 10, 2)->nullable();
            // If the transaction has order_id, it means it was initiated from the POS and it already used
            $table->unsignedBigInteger("order_id")->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('nexopos_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpesa_transactions');
    }
};
