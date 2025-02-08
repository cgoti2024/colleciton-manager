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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('order_id');
            $table->string('shopify_item_id');
            $table->string('shopify_product_id');
            $table->string('shopify_variant_id');
            $table->string('name');         
            $table->integer('quantity');
            $table->double('price')->default(0);
            $table->double('discount_amount')->default(0);
            $table->string('vendor')->nullable();
            $table->string('sku')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('shop_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
