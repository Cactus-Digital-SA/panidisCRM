<?php

use App\Domains\ExtraData\Enums\ExtraDataTypesEnum;
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
        Schema::create('extra_data', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('type')->default(ExtraDataTypesEnum::TEXT);
            $table->text('options')->nullable();
            $table->boolean('required')->default(false);
            $table->boolean('multiple')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_data');

    }
};
