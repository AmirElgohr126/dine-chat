<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\App\Auth\AccountController;

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

Route::get('/privacy', function () {
    return view('Privacy.Privacy');
});

Route::get('/terms', function () {
    return view('Privacy.Terms');
});
Route::post('/account/delete', [AccountController::class, 'delete'])->name('account.delete');
Route::get('/account', [AccountController::class, 'view']);
Route::get('/welcome', function () {
    return view('welcome');
});
