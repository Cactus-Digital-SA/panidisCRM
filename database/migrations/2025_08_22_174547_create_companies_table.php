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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('erp_id')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('activity')->nullable();

            $table->foreignId('type_id')->nullable()->constrained('company_types')->cascadeOnDelete();

            $table->foreignId('country_id')->nullable()->constrained('country_codes')->cascadeOnDelete();
            $table->string('city')->nullable();

            $table->foreignId('source_id')->nullable()->constrained('company_source')->cascadeOnDelete();

            $table->string('website')->nullable();
            $table->string('linkedin')->nullable();

            $table->decimal('current_balance', 12, 2)->default(0);

            $table->softDeletes();
            $table->timestamps();

            $table->index('name');
            $table->index('erp_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
