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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_collection_id')->unique();
            $table->unsignedBigInteger('shop_id');
            $table->string('title');
            $table->string('handle');
            $table->string('status');
            $table->dateTime('published_at')->nullable();
            $table->integer('products_count')->default(0);
            $table->string('collection_type');
            $table->string('image_url')->nullable();
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
        Schema::dropIfExists('collections');
    }
};
