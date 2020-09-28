<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\FilesController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');



Route::get('/files', [FilesController::class, 'index'])->name('user.files.index');
Route::post('/upload', [FilesController::class, 'store'])->name('user.files.store');
Route::post('/dashboard', [FilesController::class, 'store'])->name('dashboard');
Route::get('/file/{file}', [FilesController::class, 'show'])->name('user.files.show');
Route::delete('/delete-file/{file}', [FilesController::class, 'destroy'])->name('user.files.destroy');
/*
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/upload', [HomeController::class, 'upload_form'])->name('upload_form');
Route::get('/download/{filename}', [HomeController::class, 'download'])->name('download'); 
*/