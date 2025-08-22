<?php

use App\Domains\ExtraData\Enums\VisibilityEnum;
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
        Schema::create('user_extra_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extra_data_id')->constrained('extra_data')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('value');
            $table->unsignedInteger('sort')->default(0);
            $table->enum('visibility',  VisibilityEnum::values())->default(VisibilityEnum::NONE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_extra_data');
    }
};
