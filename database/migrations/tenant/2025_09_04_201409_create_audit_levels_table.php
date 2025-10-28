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
        Schema::create('audit_levels', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->integer('min')->nullable()->unique();
            $table->integer('max')->nullable()->unique();
            $table->integer('score')->unique();
            $table->string('color');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_levels');
    }
};
