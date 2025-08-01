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
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->decimal('base_salary', 10, 2);
            $table->decimal('bonuses_total', 10, 2)->default(0);
            $table->decimal('deductions_total', 10, 2)->default(0);
            $table->decimal('extra_hours_total', 10, 2)->default(0);
            $table->decimal('isss', 10, 2)->default(0);
            $table->decimal('afp', 10, 2)->default(0);
            $table->decimal('isr', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_details');
    }
};
