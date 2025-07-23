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
        Schema::create('findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('control_id')->constrained('controls');
            $table->string('title');
            $table->foreignId('audited_sub_process_id')->constrained('sub_processes');
            $table->enum('finding_type', ['major_nonconformity', 'minor_nonconformity', 'observation', 'opportunity_for_improvement']);
            $table->text('description');
            $table->text('criteria_not_met');
            $table->foreignId('responsible_auditor_id')->constrained('users');
            $table->foreignId('status_id')->constrained('statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('findings');
    }
};
