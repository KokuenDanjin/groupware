<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// カレンダー
Route::get('calendar/{type?}/{currentDate?}', [CalendarController::class, 'show'])
    ->middleware('auth')
    ->name('Calendar.view')
    ->where([
        'type' => 'month|week|day',
        'currentDate' => '\d{8}' //YYYYMMDD
    ]);

// スケジュール
Route::middleware('auth')->prefix('/schedule/{id}')->name('schedule')->group(function() {
    Route::get('', [ScheduleController::class, 'show'])->name('');
});

require __DIR__.'/auth.php';
