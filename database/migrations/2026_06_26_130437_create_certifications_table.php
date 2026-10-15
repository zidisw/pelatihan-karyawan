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
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('training_id')->nullable()->constrained('trainings')->nullOnDelete();
            $table->string('jenis_sertifikasi')->default('Lainnya');
            $table->string('nama_sertifikasi');
            $table->string('nomor_sertifikat')->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->date('tanggal_expired')->nullable();
            $table->boolean('is_permanent')->default(false);
            $table->string('file_sertifikat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
