<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::resource('tasks', TaskController::class);
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');
Route::put('/calendar/{event}', [CalendarController::class, 'update'])->name('calendar.update');
Route::delete('/calendar/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');