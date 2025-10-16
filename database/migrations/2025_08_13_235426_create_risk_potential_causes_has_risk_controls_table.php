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
        Schema::create('risk_potential_causes_has_risk_controls', function (Blueprint $table) {

            $table->foreignId('risk_potential_cause_id');
            $table->foreignId('risk_control_id');

            $table->foreign('risk_potential_cause_id', 'fk_rpc_cause')
                ->references('id')->on('risk_potential_causes')
                ->cascadeOnDelete();

            $table->foreign('risk_control_id', 'fk_rpc_control')
                ->references('id')->on('risk_controls')
                ->cascadeOnDelete();

            $table->primary(['risk_potential_cause_id', 'risk_control_id'], 'pk_rpc_has_controls');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_potential_causes_has_risk_controls');
    }
};
