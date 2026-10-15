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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik')->unique();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->string('jabatan')->nullable();
            $table->string('site_unit')->nullable();
            $table->enum('status_karyawan', ['tetap', 'kontrak'])->default('tetap');
            $table->enum('status_aktif', ['aktif', 'nonaktif'])->default('aktif');
            $table->string('email')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->date('tanggal_join')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
