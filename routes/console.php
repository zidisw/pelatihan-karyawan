<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduler harian pengecekan masa berlaku sertifikat (PRD Bab 7.3.D)
Schedule::command('certificates:check-reminders')->dailyAt('07:00');
