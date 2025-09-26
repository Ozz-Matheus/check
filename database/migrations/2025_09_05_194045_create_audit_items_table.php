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
        Schema::create('audit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_audit_id')->constrained('internal_audits');
            $table->foreignId('activity_id')->constrained('audit_sub_process_activities');
            $table->text('risk_description');
            $table->foreignId('risk_category_id')->constrained('risk_categories');
            $table->text('consequences');
            $table->foreignId('general_level_id')->nullable()->constrained('audit_levels');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_items');
    }
};
