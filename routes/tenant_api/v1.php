<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\CategoryController;

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

Route::post('/', function (Request $request) {
});

Route::prefix('categories')->name('categories')->group(function (){
    Route::post('/', [CategoryController::class, 'list'])->name('list');
    Route::post('store', [CategoryController::class, 'store'])->name('store');
    Route::post('show', [CategoryController::class, 'show'])->name('show');
    Route::post('update', [CategoryController::class, 'update'])->name('update');
    Route::post('delete', [CategoryController::class, 'delete'])->name('delete');
});
