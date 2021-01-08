<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\VideoController;

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

Route::group(['middleware' => 'auth.page'], function(){
    Route::get('/dashboard-page', [DashboardController::class, 'index']);
    Route::get('/video-page/{page}/{startDate}/{endDate}', [VideoController::class, 'index']);
    Route::get('/band-page', [BandController::class, 'index']);
    Route::get('/shop-page', [ShopController::class, 'index']);
    Route::get('/article-page', [ArticleController::class, 'index']);
    Route::get('/article-add-page', [ArticleController::class, 'articleAdd']);
    Route::get('/article-edit-page/{articleId}', [ArticleController::class, 'articleEdit']);
});

Route::get('/', [AuthController::class, 'login'])->middleware(['check.login.page']);
