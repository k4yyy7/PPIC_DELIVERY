<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_active_drivers', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_item_id')->nullable()->change();
        });
    }
    public function down(): void
    {
        Schema::table('user_active_drivers', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_item_id')->nullable(false)->change();
        });
    }
};
