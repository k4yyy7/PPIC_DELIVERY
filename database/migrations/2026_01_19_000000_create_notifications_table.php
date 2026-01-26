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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // user yang melakukan update
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade'); // admin yang menerima notif
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // data tambahan (model name, field yang diubah, dll)
            $table->enum('type', ['update', 'create', 'delete'])->default('update');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['admin_id', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
