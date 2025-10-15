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
        Schema::create('supplier_issues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('cause_id')->constrained('supplier_issue_causes');
            $table->text('description');
            $table->date('issue_date');
            $table->foreignId('supplier_id')->constrained('users');
            $table->foreignId('product_id')->constrained('supplier_products');
            $table->integer('amount');
            $table->string('supplier_lot');
            $table->date('report_date');
            $table->bigInteger('monetary_impact');
            $table->text('supplier_response')->nullable();
            $table->text('supplier_actions')->nullable();
            $table->date('response_date')->nullable();
            $table->enum('effectiveness', ['yes', 'no', 'partial'])->nullable();
            $table->text('evaluation_comment')->nullable();
            $table->date('real_evaluation_date')->nullable();
            $table->foreignId('status_id')->constrained('statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_issues');
    }
};
