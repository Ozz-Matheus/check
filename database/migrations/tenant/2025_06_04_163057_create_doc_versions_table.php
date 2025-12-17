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
        Schema::create('doc_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->string('comment')->nullable();
            $table->string('sha256_hash')->unique();

            $table->foreignId('status_id')->constrained('statuses');

            $table->foreignId('doc_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by_id')->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doc_versions');
    }
};
