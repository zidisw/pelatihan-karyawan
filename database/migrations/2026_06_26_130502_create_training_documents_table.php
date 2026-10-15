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
        Schema::create('training_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->nullable()->constrained('trainings')->cascadeOnDelete();
            $table->foreignId('training_request_id')->nullable()->constrained('training_requests')->cascadeOnDelete();
            $table->foreignId('training_participant_id')->nullable()->constrained('training_participants')->cascadeOnDelete();
            $table->enum('jenis_dokumen', ['silabus', 'brosur', 'tor', 'lampiran_request', 'bukti_kehadiran', 'evaluasi', 'lainnya'])->default('lainnya');
            $table->string('nama_file');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('ukuran_file')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_documents');
    }
};
