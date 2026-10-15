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
        Schema::create('training_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_request')->unique()->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->cascadeOnDelete();
            $table->foreignId('training_id')->nullable()->constrained('trainings')->nullOnDelete();
            $table->foreignId('diajukan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('jenis_request', ['katalog', 'training_baru'])->default('katalog');

            // Field khusus Form Pengajuan Training Baru (di luar katalog)
            $table->string('periode_pengajuan')->nullable(); // Misal: Okt 2026 / 2026-10
            $table->string('scope_program')->nullable();
            $table->text('kondisi_saat_ini')->nullable();
            $table->text('training_need')->nullable();
            $table->text('target_kpi')->nullable();
            $table->decimal('estimasi_biaya', 15, 2)->nullable();
            $table->enum('pelaksana', ['Internal', 'Eksternal'])->nullable();
            $table->enum('metode_pelaksanaan', ['Online', 'Offline', 'Hybrid'])->nullable();

            $table->enum('status', ['menunggu_review', 'pending', 'approved', 'partially_approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_requests');
    }
};
