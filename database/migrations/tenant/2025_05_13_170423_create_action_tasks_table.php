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
            $table->date('limit_date')->nullable()->index();          // fecha límite
            $table->date('real_start_date')->nullable();   // cuándo realmente empezó
            $table->date('real_closing_date')->nullable(); // cuándo cerró

            $table->foreignId('status_id')->constrained('statuses');
            $table->boolean('finished')->default(false);
            $table->text('extemporaneous_reason')->nullable();
            $table->text('reason_for_cancellation')->nullable();
            $table->date('cancellation_date')->nullable();

            $table->index(['status_id', 'limit_date']);
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
