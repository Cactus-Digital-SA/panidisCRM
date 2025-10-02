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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->string('erp_id')->nullable();
            $table->string('name')->nullable();
            $table->string('category')->nullable();
            $table->decimal('price_wholesale', 15, 2)->nullable();
            $table->decimal('price_retail', 15, 2)->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('image_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
