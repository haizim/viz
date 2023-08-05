<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardShowController;
use App\Http\Controllers\Home;
use App\Http\Controllers\DatabasesController;
use App\Http\Controllers\QueriesController;

Route::redirect('/', 'auth/login');

Route::middleware(['auth', 'verified'])
    ->group(
        function () {
            Route::get('/home', Home::class)->name('home');
            Route::resource('databases', DatabasesController::class);
            Route::resource('queries', QueriesController::class);
            Route::resource('dashboard', DashboardController::class);
        }
    );

Route::get('/d/{id}', DashboardShowController::class)->name('dashboard-show');

include __DIR__.'/auth.php';
include __DIR__.'/my.php';
