<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ProviderController;
use App\Http\Controllers\Admin\DashboardContoller;


Route::get('/', [ProviderController::class, 'index']);
Route::get('/auth/{provider}/redirect', [ProviderController::class, 'redirect']);
Route::get('/auth/{provider}/callback',[ProviderController::class, 'callback']);

Route::get('/dashboard', [DashboardContoller::class, 'index'])->middleware('auth')->name('dashboard');
Route::post('/logout', [ProviderController::class, 'logout'])->name('logout');


