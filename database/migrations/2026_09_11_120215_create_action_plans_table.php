<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('action_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('growth_diagnosis_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->integer('priority_rank');
            $table->float('priority_score')->default(0);
            $table->integer('timeline_days')->default(7);
            $table->json('target_kpi')->nullable();
            $table->string('approval_status')->default('pending'); // pending, approved, rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_plans');
    }
};
