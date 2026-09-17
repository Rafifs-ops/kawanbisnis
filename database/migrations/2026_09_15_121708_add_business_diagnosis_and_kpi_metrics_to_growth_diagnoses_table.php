<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('growth_diagnoses', function (Blueprint $table) {
            $table->text('business_diagnosis')->nullable()->after('summary_diagnosis');
            $table->json('kpi_metrics')->nullable()->after('opportunities');
        });
    }

    public function down(): void
    {
        Schema::table('growth_diagnoses', function (Blueprint $table) {
            $table->dropColumn(['business_diagnosis', 'kpi_metrics']);
        });
    }
};
