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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('deadline')->nullable();

            $table->boolean('public')->nullable()->default(false);
            $table->boolean('billable')->nullable()->default(false);

            $table->string('priority')->nullable();
            $table->string('source')->nullable();

            $table->unsignedBigInteger('est_time')->nullable()->default(0);
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('ticket_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->enum('label', LabelEnum::values())->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('visibility')->default(true);
            $table->timestamps();
        });

        Schema::create('tickets_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_status_id')->constrained('ticket_status')->cascadeOnDelete();
            $table->timestamp('date');
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('ticketables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->morphs('ticketable');
            $table->timestamps();
        });

        Schema::create('ticket_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('blocked_by_ticket_id')->constrained('tickets')->onDelete('cascade');

            $table->unique(['ticket_id', 'blocked_by_ticket_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_blocks');
        Schema::dropIfExists('tickets_statuses');
        Schema::dropIfExists('ticketables');
        Schema::dropIfExists('ticket_status');
        Schema::dropIfExists('tickets');
    }
};
