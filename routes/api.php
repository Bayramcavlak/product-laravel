<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, "index"])->name('products.index');
    Route::get('/{id}/show', [ProductController::class, "show"])->name('products.show');
    Route::post('/store', [ProductController::class, "store"])->name('products.store')->middleware('random.user');
    Route::put('/{id}/update', [ProductController::class, "update"])->name('products.update');
    Route::delete('/{id}/destroy', [ProductController::class, "destroy"])->name('products.destroy');
});
