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
        Schema::create('action_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('action_id')->constrained(); // relación con la acción
            $table->string('title');
            $table->text('detail');
            $table->foreignId('responsible_by_id')->constrained('users'); // responsable de la tarea
            $table->date('start_date');        // planificada
            $table->date('deadline');          // fecha límite
            $table->date('actual_start_date')->nullable();   // cuándo realmente empezó
            $table->date('actual_closing_date')->nullable(); // cuándo cerró

            // NUEVO: status general
            $table->foreignId('status_id')->constrained('statuses');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_tasks');
    }
};
