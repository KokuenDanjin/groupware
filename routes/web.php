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
Route::get('calendar/{type?}', [CalendarController::class, 'show'])
    ->middleware('auth')
    ->name('calendar.view')
    ->where([
        'type' => 'month|week|day'
    ]);

// スケジュール
//ID無し用
Route::middleware('auth')->prefix('/schedule')->name('schedule.')->group(function() {
    Route::get('create', [ScheduleController::class, 'create'])->name('create');
    Route::post('store', [ScheduleController::class, 'store'])->name('store');
});
// ID必須用
Route::middleware('auth')->prefix('/schedule/{id}')->name('schedule.')->group(function() {
    Route::get('', [ScheduleController::class, 'show'])->name('show');
    Route::get('edit', [ScheduleController::class, 'edit'])->name('edit');
    Route::patch('update', [ScheduleController::class, 'update'])->name('update');
    Route::delete('delete', [ScheduleController::class, 'delete'])->name('delete');
});


require __DIR__.'/auth.php';
