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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->decimal('rain')->nullable();
            $table->decimal('soil')->nullable();
            $table->decimal('light')->nullable();
            $table->decimal('temperature')->nullable();
            $table->decimal('humidity')->nullable();
            $table->boolean('relay_status')->nullable();
            $table->timestamp('time');
            $table->index('time');
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
        Schema::dropIfExists('settings');
    }
};
