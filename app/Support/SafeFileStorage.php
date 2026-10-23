<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SafeFileStorage
{
    /**
     * Menyimpan file unggahan secara aman baik di disk lokal (/tmp di Vercel)
     * maupun di tabel cache database agar permanen di environment serverless (Vercel + Neon PostgreSQL).
     */
    public static function store(UploadedFile $file, string $folder = 'certificates'): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'pdf');
        $safeFolder = trim($folder, '/');
        $filename = time() . '_' . Str::random(10) . '.' . $ext;
        $relativePath = $safeFolder . '/' . $filename;

        $realPath = $file->getRealPath();
        $binary = $realPath && is_readable($realPath) ? file_get_contents($realPath) : $file->get();
        $mimeType = $file->getClientMimeType() ?: $file->getMimeType() ?: 'application/octet-stream';
        $originalName = $file->getClientOriginalName() ?: $filename;

        // 1. Simpan ke disk public (di Vercel mengarah ke /tmp/storage/app/public)
        try {
            Storage::disk('public')->put($relativePath, $binary);
        } catch (\Throwable $e) {
            Log::warning('SafeFileStorage disk put warning: ' . $e->getMessage());
        }

        // 2. Simpan backup permanen di tabel cache database agar tidak hilang saat container Vercel restart
        try {
            $payload = json_encode([
                'mime' => $mimeType,
                'name' => $originalName,
                'data' => base64_encode($binary),
            ]);

            DB::table('cache')->updateOrInsert(
                ['key' => 'file_blob:' . $relativePath],
                [
                    'value' => $payload,
                    'expiration' => 2147483647,
                ]
            );
        } catch (\Throwable $e) {
            Log::warning('SafeFileStorage DB backup warning: ' . $e->getMessage());
        }

        return $relativePath;
    }

    /**
     * Menghapus file dari disk public dan dari tabel cache database secara aman.
     */
    public static function delete(?string $relativePath): void
    {
        if (!$relativePath) {
            return;
        }

        try {
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
        } catch (\Throwable $e) {
            Log::warning('SafeFileStorage disk delete warning: ' . $e->getMessage());
        }

        try {
            DB::table('cache')->where('key', 'file_blob:' . $relativePath)->delete();
        } catch (\Throwable $e) {
            Log::warning('SafeFileStorage DB delete warning: ' . $e->getMessage());
        }
    }

    /**
     * Mengembalikan HTTP response untuk melihat (inline) atau mengunduh (download) file.
     * Otomatis mengambil dari disk jika tersedia, atau memulihkan dari database jika di Vercel /tmp terhapus.
     */
    public static function response(?string $relativePath, string $downloadName, bool $inline = false)
    {
        if (!$relativePath) {
            return null;
        }

        // 1. Cek di disk public terlebih dahulu
        try {
            if (Storage::disk('public')->exists($relativePath)) {
                if ($inline) {
                    return Storage::disk('public')->response($relativePath, $downloadName);
                }

                return Storage::disk('public')->download($relativePath, $downloadName);
            }
        } catch (\Throwable $e) {
            Log::warning('SafeFileStorage disk read warning: ' . $e->getMessage());
        }

        // 2. Fallback: Ambil dari tabel cache database (khususnya pada Vercel Serverless)
        try {
            $record = DB::table('cache')->where('key', 'file_blob:' . $relativePath)->first();
            if ($record && !empty($record->value)) {
                $decoded = json_decode($record->value, true);
                if (is_array($decoded) && !empty($decoded['data'])) {
                    $binary = base64_decode($decoded['data'], true);
                    if ($binary !== false) {
                        $mime = $decoded['mime'] ?? 'application/pdf';
                        $disposition = ($inline ? 'inline' : 'attachment') . '; filename="' . addslashes($downloadName) . '"';

                        return response($binary, 200, [
                            'Content-Type' => $mime,
                            'Content-Length' => strlen($binary),
                            'Content-Disposition' => $disposition,
                            'Cache-Control' => 'private, max-age=3600',
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('SafeFileStorage DB read warning: ' . $e->getMessage());
        }

        return null;
    }
}
