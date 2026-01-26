<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_active_drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('driver_item_id');
            $table->date('date');
            $table->timestamps();
            $table->unique(['user_id', 'date']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('driver_item_id')->references('id')->on('driver_items')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('user_active_drivers');
    }
};
