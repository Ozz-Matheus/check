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
        Schema::create('risk_treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_id')->constrained('risks');
            $table->foreignId('responsible_executor_id')->constrained('users'); // 📌Se le puede hacer una llamada directamente de el lider del subproceso
            $table->foreignId('risk_control_general_qualification_id')->constrained('risk_control_qualifications');
            $table->foreignId('residual_risk_calculated_level_id')->constrained('risk_levels');
            $table->foreignId('residual_impact_id')->constrained('risk_impacts');
            $table->foreignId('residual_probability_id')->constrained('risk_probabilities');
            $table->foreignId('residual_risk_level_id')->constrained('risk_levels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_treatments');
    }
};
