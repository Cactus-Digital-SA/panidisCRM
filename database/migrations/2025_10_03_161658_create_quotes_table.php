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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('reference_code')->unique()->nullable();

            $table->string('title')->nullable();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();

            $table->string('status')->nullable();
            $table->date('valid_until')->nullable();

            $table->string('payment_terms')->nullable();
            $table->string('delivery_terms')->nullable();

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->string('tax_rate')->nullable();
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('uuid');
            $table->index('reference_code');
            $table->index('company_id');
            $table->index('status');
            $table->index('valid_until');
        });

        Schema::create('quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();

            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->string('color')->nullable();
            $table->string('unit_type')->nullable();

            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('quantity', 12, 2)->default(1);

            $table->decimal('total', 12, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('quote_id');
            $table->index('item_id');
        });

        Schema::create('quote_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained('quotes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('quote_contacts');
        Schema::dropIfExists('quote_items');
        Schema::dropIfExists('quotes');
    }
};
