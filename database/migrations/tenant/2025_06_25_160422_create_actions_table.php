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
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('action_type_id')->constrained();   // nuevo: tipo de acción
            $table->foreignId('finding_id')->nullable()->constrained();
            $table->string('title');
            $table->text('description');

            $table->foreignId('process_id')->constrained();
            $table->foreignId('sub_process_id')->constrained();
            $table->foreignId('action_origin_id')->constrained();

            $table->foreignId('registered_by_id')->constrained('users');
            $table->foreignId('responsible_by_id')->constrained('users');

            // Correctiva / Preventiva
            $table->date('detection_date')->nullable();

            // Solo para Correctiva
            $table->text('containment_action')->nullable();
            $table->foreignId('action_analysis_cause_id')->nullable()->constrained('action_analysis_causes');
            $table->text('corrective_action')->nullable();
            $table->foreignId('action_verification_method_id')->nullable()->constrained('action_verification_methods');
            $table->foreignId('verification_responsible_by_id')->nullable()->constrained('users');
            $table->date('verification_date')->nullable();

            // Solo para Preventiva
            $table->unsignedTinyInteger('risk_probability')->nullable();
            $table->unsignedTinyInteger('risk_impact')->nullable();
            $table->unsignedTinyInteger('risk_evaluation')->nullable();

            $table->text('prevention_action')->nullable();
            $table->text('effectiveness_indicator')->nullable(); // Posibilidad dejarlo como un select, indicadores comunes por proceso (ej. "Tasa de fallas", "N° de reportes", etc.), pero también permitir definir nuevos.

            // Mejora / Preventiva
            $table->text('expected_impact')->nullable();

            $table->foreignId('status_id')->constrained('statuses');
            $table->date('deadline');
            $table->date('actual_closing_date')->nullable();
            $table->text('reason_for_cancellation')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
