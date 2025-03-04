<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('documentsign/document-sign/{id}/{receipt_id}', [Modules\DocumentSign\Http\Controllers\DocumentSignController::class, 'sign']);
Route::middleware('web', 'authh', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu')->group(function () {
    Route::prefix('documentsign')->group(function () {

        Route::resource('document', 'Modules\DocumentSign\Http\Controllers\DocumentSignController');

        Route::get('/install', [Modules\DocumentSign\Http\Controllers\InstallController::class, 'index']);
        Route::get('/install/update', [Modules\DocumentSign\Http\Controllers\InstallController::class, 'update']);
        Route::get('/install/uninstall', [Modules\DocumentSign\Http\Controllers\InstallController::class, 'uninstall']);
        Route::post('/uploadDocs', [Modules\DocumentSign\Http\Controllers\DocumentSignController::class, 'uploadDocs']);
        Route::post('/removeDocs', [Modules\DocumentSign\Http\Controllers\DocumentSignController::class, 'removeDocs']);
    });
});
