<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_passport_id')->constrained()->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('revenue', 15, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('average_order_value', 15, 2)->default(0);
            $table->json('new_vs_returning_customers')->nullable(); // {new: n, returning: n}
            $table->json('product_performances')->nullable(); // [{name, quantity, revenue}]
            $table->string('raw_uploaded_file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_snapshots');
    }
};
