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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_program')->unique()->nullable();
            $table->string('nama_pelatihan');
            $table->enum('kategori', ['Sertifikasi', 'Technical Skill', 'Soft Skill', 'Lainnya'])->default('Technical Skill');
            $table->string('jenis_sertifikasi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('instruktur')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->date('batas_pendaftaran')->nullable();
            $table->integer('kuota')->default(0);
            $table->string('lokasi')->nullable();
            $table->enum('metode_pelaksanaan', ['OFFLINE', 'ONLINE', 'HYBRID'])->default('OFFLINE');
            $table->enum('status_pendaftaran', ['belum_dibuka', 'dibuka', 'ditutup'])->default('dibuka');
            $table->enum('status_program', ['draft', 'akan_datang', 'berjalan', 'selesai', 'dibatalkan'])->default('akan_datang');
            $table->text('persyaratan_peserta')->nullable();
            $table->json('target_departemen')->nullable();
            $table->decimal('estimasi_biaya', 15, 2)->nullable();
            $table->text('catatan_internal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
