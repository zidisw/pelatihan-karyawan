<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        // Auto-migrate & seed database di Production (Vercel + Neon) jika skema terbaru belum tersedia
        if (! $this->app->runningInConsole()) {
            $this->ensureDatabaseSchemaUpToDate();
        }
    }

    /**
     * Pastikan skema database (Issue #1 & #2) sudah teraplikasi di environment Production.
     */
    protected function ensureDatabaseSchemaUpToDate(): void
    {
        $lockFile = sys_get_temp_dir() . '/racademy_schema_v2_20261015.lock';

        if (file_exists($lockFile)) {
            return;
        }

        try {
            $hasLatestSchema = Schema::hasTable('audit_logs')
                && Schema::hasTable('training_request_participants')
                && Schema::hasColumn('employees', 'level_jabatan')
                && Schema::hasColumn('certifications', 'masa_berlaku')
                && Schema::hasColumn('trainings', 'kategori');

            if (! $hasLatestSchema) {
                Artisan::call('migrate:fresh', [
                    '--seed' => true,
                    '--force' => true,
                ]);
            }

            @file_put_contents($lockFile, (string) time());
        } catch (\Throwable $e) {
            Log::error('Auto-migration check failed: ' . $e->getMessage());
        }
    }
}

