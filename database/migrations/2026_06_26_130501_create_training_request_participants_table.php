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
        Schema::create('training_request_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_request_id')->constrained('training_requests')->cascadeOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->cascadeOnDelete();
            $table->string('nama_peserta')->nullable();
            $table->string('jabatan_peserta')->nullable();
            $table->enum('status_peserta', ['menunggu_review', 'pending', 'approved', 'rejected'])->default('menunggu_review');
            $table->text('catatan_keputusan')->nullable();
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
        Schema::dropIfExists('training_request_participants');
    }
};
