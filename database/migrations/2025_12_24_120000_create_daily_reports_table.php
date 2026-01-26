<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Polymorphic subject (driver_items or armada_items)
            $table->unsignedBigInteger('subject_id');
            $table->string('subject_type');
            $table->enum('status', ['OK', 'NG', 'UNKNOWN'])->default('UNKNOWN');
            $table->string('image_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['date', 'user_id', 'subject_id', 'subject_type'], 'daily_reports_unique_per_day');
            $table->index(['subject_type', 'subject_id']);
            $table->index(['date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
