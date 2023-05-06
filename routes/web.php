<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index ');
})->name('home');

Route::get('/incidentTypes', [App\Http\Controllers\IncidentTypeController::class, 'index'])->name('incidentTypes.index');
