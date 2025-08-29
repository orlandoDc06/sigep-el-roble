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
        Schema::create('isr_ranges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legal_configuration_id')->constrained()->onDelete('cascade');
            $table->decimal('min_amount', 10, 2)->comment('Monto mínimo del rango');
            $table->decimal('max_amount', 10, 2)->nullable()->comment('Monto máximo del rango (null = sin límite)');
            $table->decimal('percentage', 5, 2)->comment('Porcentaje a aplicar');
            $table->decimal('fixed_fee', 10, 2)->default(0)->comment('Cuota fija del rango');
            $table->timestamps();

            // Índices
            $table->index(['legal_configuration_id', 'min_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('isr_ranges');
    }
};