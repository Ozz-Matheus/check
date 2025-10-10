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
            $table->nullableMorphs('origin');
            $table->string('origin_label')->nullable();

            $table->string('title');
            $table->text('description');

            $table->foreignId('action_type_id')->constrained();
            $table->foreignId('source_id')->nullable()->constrained('action_sources');

            $table->foreignId('process_id')->constrained();
            $table->foreignId('sub_process_id')->constrained();

            $table->foreignId('registered_by_id')->constrained('users');
            $table->foreignId('responsible_by_id')->constrained('users');

            // Correctiva
            $table->date('detection_date')->nullable();
            $table->foreignId('action_analysis_cause_id')->nullable()->constrained('action_analysis_causes');
            $table->text('root_cause')->nullable();
            $table->text('containment_actions')->nullable();
            $table->foreignId('action_verification_method_id')->nullable()->constrained('action_verification_methods');
            $table->foreignId('verification_responsible_by_id')->nullable()->constrained('users');

            // Mejora
            $table->text('expected_impact')->nullable();

            // $table->foreignId('priority_id')->constrained('priorities'); integrar
            $table->date('limit_date')->nullable()->index();
            $table->foreignId('status_id')->constrained('statuses');
            $table->boolean('finished')->default(false);

            $table->text('reason_for_cancellation')->nullable();
            $table->date('cancellation_date')->nullable();

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
