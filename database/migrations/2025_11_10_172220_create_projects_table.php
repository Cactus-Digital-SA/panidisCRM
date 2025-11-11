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
        Schema::create('project_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon');
            $table->string('slug')->unique();
            $table->boolean('visibility')->default(true);
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->double('sales_cost')->nullable();

            $table->string('google_drive')->nullable();
            $table->enum('priority', \App\Domains\Projects\Enums\ProjectPriorityEnum::values())->nullable();
            $table->date('est_date')->nullable();

            $table->unsignedBigInteger('est_time')->nullable()->default(0);
            $table->foreignId('type_id')->constrained('project_types')->cascadeOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();

            $table->string('category')->nullable();
            $table->string('category_status')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->enum('label', \App\Helpers\Enums\LabelEnum::values())->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('visibility')->default(true);
            $table->timestamps();
        });

        Schema::create('projects_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_status_id')->constrained('project_status')->cascadeOnDelete();
            $table->timestamp('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects_statuses');
        Schema::dropIfExists('project_status');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_types');
    }
};
