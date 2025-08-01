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
        Schema::create('legal_configurations', function (Blueprint $table) {
            $table->id();
            $table->decimal('afp_percentage', 5, 2);// % AFP
            $table->decimal('isss_percentage', 5, 2);// % ISSS
            $table->decimal('isss_max_cap', 10, 2);// Techo máximo para ISSS
            $table->decimal('minimum_wage', 10, 2);// Salario mínimo
            $table->decimal('vacation_bonus_percentage', 5, 2);// % bono vacacional
            $table->integer('year_end_bonus_days');// Días de aguinaldo según ley
            $table->boolean('income_tax_enabled')->default(true);// Si aplica retención de renta
            $table->date('start_date');// Fecha desde que aplica esta config
            $table->date('end_date')->nullable();// Fecha fin (si es reemplazada)
            $table->boolean('is_active')->default(true);// Activa actualmente
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_configurations');
    }
};
