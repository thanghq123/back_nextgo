<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenant\CategoryController;
use App\Http\Controllers\Tenant\WarrantyController;
use App\Http\Controllers\Tenant\GroupCustomerController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\ItemUnitController;
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

Route::prefix('categories')->middleware('cors')->name('categories')->group(function (){
    Route::post('/', [CategoryController::class, 'list'])->name('list');
    Route::post('store', [CategoryController::class, 'store'])->name('store');
    Route::post('show', [CategoryController::class, 'show'])->name('show');
    Route::post('update', [CategoryController::class, 'update'])->name('update');
    Route::post('delete', [CategoryController::class, 'delete'])->name('delete');
});

Route::prefix('warranties')->middleware('cors')->name('warranties')->group(function (){
    Route::post('/', [WarrantyController::class, 'list'])->name('list');
    Route::post('store', [WarrantyController::class, 'store'])->name('store');
    Route::post('show', [WarrantyController::class, 'show'])->name('show');
    Route::post('update', [WarrantyController::class, 'update'])->name('update');
    Route::post('delete', [WarrantyController::class, 'delete'])->name('delete');
});

Route::prefix('item_units')->middleware('cors')->name('item_units')->group(function (){
    Route::post('/', [ItemUnitController::class, 'list'])->name('list');
    Route::post('store', [ItemUnitController::class, 'store'])->name('store');
    Route::post('show', [ItemUnitController::class, 'show'])->name('show');
    Route::post('update', [ItemUnitController::class, 'update'])->name('update');
    Route::post('delete', [ItemUnitController::class, 'delete'])->name('delete');
});

Route::prefix('group_customers')->name('group_customers')->group(function (){
    Route::post('/', [GroupCustomerController::class, 'list'])->name('list');
    Route::post('store', [GroupCustomerController::class, 'store'])->name('store');
    Route::post('show', [GroupCustomerController::class, 'show'])->name('show');
    Route::post('update', [GroupCustomerController::class, 'update'])->name('update');
    Route::post('delete', [GroupCustomerController::class, 'delete'])->name('delete');
});

Route::prefix('customers')->name('customers')->group(function (){
    Route::post('/', [CustomerController::class, 'list'])->name('list');
    Route::post('store', [CustomerController::class, 'store'])->name('store');
    Route::post('show', [CustomerController::class, 'show'])->name('show');
    Route::post('update', [CustomerController::class, 'update'])->name('update');
    Route::post('delete', [CustomerController::class, 'delete'])->name('delete');
});
