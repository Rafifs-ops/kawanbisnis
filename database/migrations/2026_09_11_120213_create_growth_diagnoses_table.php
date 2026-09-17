<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('growth_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('growth_goal_id')->constrained()->cascadeOnDelete();
            $table->text('summary_diagnosis');
            $table->json('key_findings')->nullable();
            $table->json('root_causes')->nullable();
            $table->json('opportunities')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('growth_diagnoses');
    }
};
