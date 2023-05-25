<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('incidents.map');
    }
    return view('welcome');
})->name('/');


Route::resource('incidentTypes', App\Http\Controllers\IncidentTypeController::class);

Route::get('incidents', [App\Http\Controllers\IncidentController::class, 'index'])->name('incidents.index');
Route::get('incidents/map', [App\Http\Controllers\IncidentController::class, 'map'])->name('incidents.map');
Route::get('incidents/create', [App\Http\Controllers\IncidentController::class, 'create'])->name('incidents.create');
Route::post('incidents', [App\Http\Controllers\IncidentController::class, 'store'])->name('incidents.store');
Route::delete('incidents/{incident}', [App\Http\Controllers\IncidentController::class, 'destroy'])->name('incidents.destroy');
Route::patch('incidents/{incident}/resolve', [App\Http\Controllers\IncidentController::class, 'resolve'])->name('incidents.resolve');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
