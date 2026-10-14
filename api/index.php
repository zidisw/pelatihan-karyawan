<?php

// Pastikan folder penyimpanan sementara di /tmp tersedia untuk runtime serverless Vercel
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Teruskan request Vercel ke index.php bawaan Laravel
require __DIR__ . '/../public/index.php';
