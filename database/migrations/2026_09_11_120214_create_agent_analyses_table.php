<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('growth_diagnosis_id')->constrained()->cascadeOnDelete();
            $table->string('agent_type'); // analytics, customer, marketing, strategy
            $table->text('question_answered');
            $table->json('findings')->nullable();
            $table->json('hypotheses')->nullable();
            $table->float('confidence_score')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_analyses');
    }
};
