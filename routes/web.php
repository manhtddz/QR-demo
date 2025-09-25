<?php

use App\Http\Controllers\QrScanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('scan');
});
Route::get('/monitor', function () {
    return view('monitor');
});
Route::post('/scan-qr', [QrScanController::class, 'upload']);
Route::post('/scan-qr-by-camera', [QrScanController::class, 'scan']);

Route::get('/api/process-qr/{id}', [QrScanController::class, 'getContractByCustomerId']);
Route::post('/process-token', [QrScanController::class, 'processToken']);
Route::get('/qr', function () {
    return view('qr_form');
});
Route::post('/generate-qr', [QrScanController::class, 'handleCreateQR']);
