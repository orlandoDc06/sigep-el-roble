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
        Schema::create('change_logs', function (Blueprint $table) {
            $table->id();
            $table->string('model'); // Ej: 'Employee', 'Attendance', etc.
            $table->unsignedBigInteger('model_id'); // ID del registro modificado
            $table->string('field_changed'); // Nombre del campo modificado
            $table->text('old_value')->nullable(); // Valor anterior
            $table->text('new_value')->nullable(); // Valor nuevo
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_logs');
    }
};
