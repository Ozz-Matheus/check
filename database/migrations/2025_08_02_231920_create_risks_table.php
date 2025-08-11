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
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_plan_id')->constrained('risk_plans')->onDelete('cascade');
            $table->foreignId('strategic_context_type_id')->constrained('risk_strategic_context_types');
            $table->foreignId('strategic_context_id')->constrained('risk_strategic_contexts');
            $table->text('risk_description');
            $table->foreignId('risk_category_id')->constrained('risk_categories');
            // $table->foreignId('risk_potential_causes') uno a muchos;
            $table->text('consequences');
            $table->foreignId('inherent_impact_id')->constrained('risk_impacts');
            $table->foreignId('inherent_probability_id')->constrained('risk_probabilities');
            $table->foreignId('inherent_risk_level_id')->constrained('risk_levels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
