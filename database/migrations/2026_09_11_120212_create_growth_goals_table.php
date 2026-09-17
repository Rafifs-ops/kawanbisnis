<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('growth_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_passport_id')->constrained()->cascadeOnDelete();
            $table->string('goal_type'); // Increase Sales, Retention, AOV, Margin
            $table->json('target_metrics')->nullable(); // {target: 5000000, unit: 'nominal'}
            $table->string('status')->default('active'); // active, achieved, expired
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('growth_goals');
    }
};
