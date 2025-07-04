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
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->string('audit_code');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('objective');
            $table->text('scope');
            $table->foreignId('involved_process_id')->constrained('processes');
            $table->foreignId('leader_auditor_id')->constrained('users');
            $table->foreignId('status_id')->constrained('statuses');
            $table->foreignId('audit_criteria_id')->constrained('audit_criterias');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
