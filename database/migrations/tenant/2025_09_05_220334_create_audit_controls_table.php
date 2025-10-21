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
        Schema::create('audit_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_item_id')->constrained('audit_items');
            $table->string('title');
            $table->foreignId('nature_of_control_id')->constrained('audit_nature_of_controls');
            $table->foreignId('control_type_id')->constrained('risk_control_types');
            $table->foreignId('control_periodicity_id')->constrained('risk_control_periodicities');
            $table->text('tests_to_validate_control');
            $table->foreignId('effect_type_id')->constrained('audit_effect_types');
            $table->foreignId('impact_id')->nullable()->constrained('audit_impacts');
            $table->foreignId('probability_id')->nullable()->constrained('audit_probabilities');
            $table->foreignId('level_id')->nullable()->constrained('audit_levels');
            $table->foreignId('classification_id')->nullable()->constrained('audit_control_classifications');
            $table->boolean('qualified')->default(false);
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_controls');
    }
};
