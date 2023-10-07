<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
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
Route::get('/test',[TestController::class,'test']);
Route::get('/mail',[TestController::class,'index']);
Route::get('/create',[TestController::class,'create']);
Route::get('/list',[TestController::class,'list']);
Route::post('/upload',[TestController::class,'upload'])->name('upload');
Route::get('/rename',[TestController::class,'rename']);
Route::get('/delete',[TestController::class,'delete']);
Route::prefix('admin')->name('admin.')->group(function (){
    Route::view('/','admin.dashboard.index')->name('home');
    Route::prefix('user-profile')->name('us-profile.')->group(function (){
        Route::view('overview','admin.user-profile.overview')->name('overview');
        Route::view('project','admin.user-profile.project')->name('project');
        Route::view('campaign','admin.user-profile.campaign')->name('campaign');
        Route::view('document','admin.user-profile.document')->name('document');
        Route::view('follower','admin.user-profile.follower')->name('follower');
        Route::view('activity','admin.user-profile.activities')->name('activity');
    });
    Route::prefix('account')->name('account.')->group(function (){
        Route::view('activity','admin.account.activity')->name('activity');
        Route::view('api-key','admin.account.apikey')->name('api-key');
        Route::view('billing','admin.account.billing')->name('billing');
        Route::view('log','admin.account.log')->name('log');
        Route::view('overview','admin.account.overview')->name('overview');
        Route::view('referral','admin.account.referral')->name('referral');
        Route::view('security','admin.account.security')->name('security');
        Route::view('setting','admin.account.setting')->name('setting');
        Route::view('statement','admin.account.statement')->name('statement');
    });
    Route::prefix('pricing')->name('pricing.')->group(function (){
        Route::view('list','admin.pricing.list')->name('list');
    });
});
