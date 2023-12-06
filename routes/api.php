<?php

use App\Http\Controllers\ProductsReservationController;
use App\Http\Controllers\ReleaseProductsReserveController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsCountController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('products')->group(function () {
    Route::get('/count', ProductsCountController::class);
    Route::post('/reservation', ProductsReservationController::class);
    Route::post('/releaseReserve', ReleaseProductsReserveController::class);
});
