<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [Controller::class, 'index'])->name('main');
Route::post('/buy', [PurchaseController::class, 'buy'])->name('buy');
Route::post('/ordering', [PurchaseController::class, 'placeOrder'])->name('place_order');

