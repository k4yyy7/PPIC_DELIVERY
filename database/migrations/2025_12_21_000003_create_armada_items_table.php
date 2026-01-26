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
        Schema::create('armada_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cars_id');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->longText('safety_items')->nullable();
            $table->longText('standard_items')->nullable();
            $table->enum('status', ['OK', 'NG'])->default('OK');
            $table->longText('image_evidence')->nullable();
            $table->timestamps();

            $table->foreign('cars_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('armada_items');
    }
};
