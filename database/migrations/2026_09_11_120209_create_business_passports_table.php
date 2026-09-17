<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_passports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('business_name');
            $table->string('business_type'); // F&B, Fashion, Retail, Service
            $table->string('target_customer')->nullable();
            $table->text('business_description')->nullable();
            $table->json('products')->nullable(); // [{name, price, margin}]
            $table->json('sales_channels')->nullable(); // [Store, Instagram, Tokopedia]
            $table->json('constraints')->nullable(); // {marketing_budget, team_capacity}
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_passports');
    }
};
