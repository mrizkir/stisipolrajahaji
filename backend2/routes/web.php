<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Frontend\FrontendDashboardController::class, 'index'])->name('frontend-dashboard.index');
