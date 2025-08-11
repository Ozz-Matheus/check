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
        Schema::create('risk_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_treatment_id')->constrained('risk_treatments');
            // $table->foreignId('potential_cause_id')->constrained('risk_potential_causes'); muchos a muchos
            $table->string('title');
            $table->foreignId('control_periodicity_id')->constrained('risk_control_periodicities');
            $table->foreignId('control_type_id')->constrained('risk_control_types');
            $table->foreignId('control_qualification_id')->constrained('risk_control_qualifications');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_controls');
    }
};
