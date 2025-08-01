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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('dui')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('hire_date');
            $table->date('termination_date')->nullable();

            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();

            $table->string('photo_path')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('contract_type_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
