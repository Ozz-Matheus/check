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
        Schema::create('risk_control_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_control_id')->constrained('risk_controls');
            $table->text('content');
            $table->foreignId('control_qualification_id')->constrained('risk_control_qualifications');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_control_follow_ups');
    }
};
