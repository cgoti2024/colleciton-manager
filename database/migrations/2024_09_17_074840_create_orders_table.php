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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->string('shopify_order_id');
            $table->string('order_number');
            $table->double('total_price')->default(0);
            $table->string('currency')->nullable();
            $table->string('gateway')->nullable();
            $table->string('tags')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->json('customer_details')->nullable();
            $table->json('shipping_address')->nullable();
            $table->string('financial_status');
            $table->string('fulfillment_status')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
