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
            $table->string('classification_code')->unique();
            $table->string('title')->unique();
            $table->foreignId('process_id')->constrained();
            $table->foreignId('sub_process_id')->constrained();
            $table->foreignId('doc_type_id')->constrained();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('central_expiration_date')->nullable();
            $table->foreignId('storage_method_id')->nullable()->constrained('doc_storages');
            $table->foreignId('recovery_method_id')->nullable()->constrained('doc_recoveries');
            $table->foreignId('disposition_method_id')->nullable()->constrained('doc_dispositions');
            // $table->foreignId('retention_period_id')->nullable()->constrained('doc_retentions');
            $table->boolean('display_restriction')->default(false);
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
