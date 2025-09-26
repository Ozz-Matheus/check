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
            $table->id();
            $table->foreignId('audit_potential_cause_id');
            $table->foreignId('audit_control_id');
            $table->timestamps();
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
