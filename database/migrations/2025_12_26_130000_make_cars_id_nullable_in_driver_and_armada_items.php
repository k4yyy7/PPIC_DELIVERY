<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('driver_items', function (Blueprint $table) {
            $table->dropForeign(['cars_id']);
            $table->unsignedBigInteger('cars_id')->nullable()->change();
            $table->foreign('cars_id')->references('id')->on('cars')->onDelete('set null');
        });

        Schema::table('armada_items', function (Blueprint $table) {
            $table->dropForeign(['cars_id']);
            $table->unsignedBigInteger('cars_id')->nullable()->change();
            $table->foreign('cars_id')->references('id')->on('cars')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('driver_items', function (Blueprint $table) {
            $table->dropForeign(['cars_id']);
            $table->unsignedBigInteger('cars_id')->nullable(false)->change();
            $table->foreign('cars_id')->references('id')->on('cars')->onDelete('cascade');
        });

        Schema::table('armada_items', function (Blueprint $table) {
            $table->dropForeign(['cars_id']);
            $table->unsignedBigInteger('cars_id')->nullable(false)->change();
            $table->foreign('cars_id')->references('id')->on('cars')->onDelete('cascade');
        });
    }
};
