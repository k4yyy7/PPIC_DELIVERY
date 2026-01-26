<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_active_drivers', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_id')->nullable()->after('driver_item_id');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
        });
    }
    public function down(): void
    {
        Schema::table('user_active_drivers', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn('driver_id');
        });
    }
};
