<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// ✅ WAJIB UNTUK CSRF SANCTUM
Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
});

require __DIR__ . '/auth.php';

Route::get('/debug-log', function () {
    Log::info('Tes log dari Laravel berhasil!');
    return 'Log berhasil ditulis!';
});
