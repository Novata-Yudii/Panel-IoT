<?php

use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaklarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TemperatureController;

Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('dashboard');
    Route::get('/dokumentasi', 'dokumentasi')->name('dokumentasi');
});
Route::controller(UserController::class)->group(function () {
    Route::get('/user', 'index')->name('user');
});
Route::controller(DeviceController::class)->group(function () {
    Route::get('/device', 'index')->name('device');
});
Route::controller(SaklarController::class)->group(function () {
    Route::get('/saklar', 'index')->name('saklar');
    Route::get('/custom/{code}', 'custom')->name('custom');
});
Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login');
});
Route::controller(TemperatureController::class)->group(function(){
    Route::get('/temperature','index')->name('temperature');
});

Route::get('/temperature', [TemperatureController::class, 'index']);