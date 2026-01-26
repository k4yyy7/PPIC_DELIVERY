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
        Schema::create('dokuments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cars_id')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->longText('safety_items')->nullable();
            $table->longText('standard_items')->nullable();
            $table->enum('status', ['OK','NG','UNKNOWN'])->default('UNKNOWN');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokuments');
    }
};
