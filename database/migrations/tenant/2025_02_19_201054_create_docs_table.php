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
        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->foreignId('process_id')->constrained();
            $table->foreignId('sub_process_id')->constrained();
            $table->foreignId('doc_type_id')->constrained();
            $table->string('classification_code')->unique();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('management_review_date')->nullable();
            $table->date('central_expiration_date')->nullable();
            $table->foreignId('doc_ending_id')->constrained();
            $table->boolean('expiration')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docs');
    }
};
