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
        Schema::create('supplier_issue_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('s_i_id')->constrained('supplier_issues');
            $table->text('supplier_response');
            $table->text('supplier_actions');
            $table->date('response_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_issue_responses');
    }
};
