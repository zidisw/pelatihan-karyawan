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
        Schema::create('training_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_request_id')->nullable()->constrained('training_requests')->nullOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->enum('status_peserta', ['belum_diajukan', 'diajukan', 'disetujui', 'terjadwal', 'sedang_pelatihan', 'selesai', 'tidak_hadir', 'dibatalkan'])->default('belum_diajukan');
            $table->enum('kehadiran', ['belum_dicatat', 'hadir', 'tidak_hadir', 'izin'])->default('belum_dicatat');
            $table->text('hasil_evaluasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_participants');
    }
};
