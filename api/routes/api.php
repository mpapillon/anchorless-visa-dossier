<?php

use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

Route::apiResource('file-uploads', FileUploadController::class)
    ->only(['index', 'store', 'show', 'destroy']);

Route::get('file-uploads/{fileUpload}/download', [FileUploadController::class, 'download'])
    ->name('file-uploads.download');
