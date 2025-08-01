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
            $table->decimal('min_amount', 10, 2);
            $table->decimal('max_amount', 10, 2)->nullable(); // null para rango abierto superior
            $table->decimal('percentage', 5, 2); // % aplicado al excedente del mínimo
            $table->decimal('fixed_fee', 10, 2); // cuota fija según tabla
            $table->timestamps();
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
