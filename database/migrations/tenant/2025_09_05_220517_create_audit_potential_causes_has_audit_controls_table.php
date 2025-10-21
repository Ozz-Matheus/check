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
        Schema::create('audit_potential_causes_has_audit_controls', function (Blueprint $table) {
            $table->foreignId('audit_control_id')
                ->constrained('audit_controls', 'id', indexName: 'fk_apc_audit_control')
                ->cascadeOnDelete();

            $table->foreignId('audit_potential_cause_id')
                ->constrained('audit_potential_causes', 'id', indexName: 'fk_apc_potential_cause')
                ->cascadeOnDelete();

            $table->primary(['audit_potential_cause_id', 'audit_control_id'], 'pk_apc_has_controls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_potential_causes_has_audit_controls');
    }
};
