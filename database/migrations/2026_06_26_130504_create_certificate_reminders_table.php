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
        Schema::create('certificate_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certification_id')->constrained('certifications')->cascadeOnDelete();
            $table->enum('tipe_reminder', ['H-90', 'H-60', 'H-30', 'H-14', 'H-7', 'H-0', 'EXPIRED']);
            $table->date('tanggal_reminder');
            $table->enum('status_pengiriman', ['terkirim', 'gagal'])->default('terkirim');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_reminders');
    }
};
