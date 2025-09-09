<?php

use App\Helpers\Enums\LabelEnum;
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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('deadline')->nullable();

            $table->string('priority')->nullable();

            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();

            // Visit fields
            $table->dateTime('visit_date')->nullable();
            $table->string('visit_type')->nullable();
            $table->double('outcome')->nullable();
            $table->string('products_discussed')->nullable();
            $table->string('next_action')->nullable();
            $table->text('next_action_comment')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('visit_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->enum('label', LabelEnum::values())->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('visibility')->default(true);
            $table->timestamps();
        });

        Schema::create('visits_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_status_id')->constrained('visit_status')->cascadeOnDelete();
            $table->timestamp('date');
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('visit_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('visits')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('visit_contacts');
        Schema::dropIfExists('visits_statuses');
        Schema::dropIfExists('visit_status');
        Schema::dropIfExists('visits');
    }
};
