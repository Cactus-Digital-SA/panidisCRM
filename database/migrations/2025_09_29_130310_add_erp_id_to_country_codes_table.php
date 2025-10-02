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
        Schema::table('country_codes', function (Blueprint $table) {
            if (!Schema::hasColumn('country_codes', 'erp_id')) {
                $table->string('erp_id')->nullable()->after('id');
                $table->string('iso_code')->nullable()->after('erp_id');
                $table->string('code')->nullable()->change();

                $table->index('erp_id');
                $table->index('iso_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('country_codes', function (Blueprint $table) {
            $table->dropColumn('erp_id');
            $table->dropColumn('iso_code');
        });
    }
};
