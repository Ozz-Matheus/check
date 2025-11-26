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
        Schema::create('internal_audits', function (Blueprint $table) {
            $table->id();
            $table->string('classification_code')->unique();
            $table->string('title');
            $table->foreignId('process_id')->constrained('processes');
            $table->foreignId('sub_process_id')->nullable()->constrained('sub_processes');
            $table->text('objective');
            $table->text('scope');
            $table->date('audit_date');
            $table->foreignId('priority_id')->constrained('priorities');
            $table->foreignId('status_id')->constrained('statuses');
            $table->foreignId('internal_audit_qualification_id')->nullable()->constrained('internal_audit_qualifications');
            $table->integer('qualification_value')->nullable();
            $table->text('observations')->nullable();
            $table->foreignId('created_by_id')->constrained('users');
            $table->foreignId('evaluated_by_id')->nullable()->constrained('users');
            $table->foreignId('headquarter_id')
                ->constrained()
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_audits');
    }
};
