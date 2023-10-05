<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;

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

Route::get('/', function (Request $request) {
    return 1;
});

Route::prefix('category')->name('category')->group(function (){
    Route::get('/', [CategoriesController::class, 'list'])->name('list');
    Route::post('store', [CategoriesController::class, 'store'])->name('store');
    Route::get('show/{id}', [CategoriesController::class, 'show'])->name('show');
    Route::put('update/{id}', [CategoriesController::class, 'update'])->name('update');
    Route::delete('delete/{id}', [CategoriesController::class, 'destroy'])->name('destroy');
});
