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
            $table->decimal('afp_percentage', 5, 2)->default(6.25)->comment('Porcentaje AFP');
            $table->decimal('isss_percentage', 5, 2)->default(3.00)->comment('Porcentaje ISSS');
            $table->decimal('isss_max_cap', 10, 2)->default(1000.00)->comment('Tope máximo ISSS');
            $table->decimal('minimum_wage', 10, 2)->default(365.00)->comment('Salario mínimo');
            $table->decimal('vacation_bonus_percentage', 5, 2)->default(30.00)->comment('Porcentaje bono vacacional');
            $table->integer('year_end_bonus_days')->default(15)->comment('Días de aguinaldo');
            $table->boolean('income_tax_enabled')->default(true)->comment('ISR habilitado');
            $table->date('start_date')->comment('Fecha inicio vigencia');
            $table->date('end_date')->nullable()->comment('Fecha fin vigencia');
            $table->boolean('is_active')->default(false)->comment('Configuración activa');
            $table->timestamps();

            // Índices
            $table->index(['is_active', 'start_date']);
            $table->index('start_date');
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