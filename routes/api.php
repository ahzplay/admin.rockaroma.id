<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\VideoController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login-auth', [AuthController::class, 'loginAuth']);
Route::post('/login-action', [AuthController::class, 'doLogin']);
Route::get('/logout-auth', [AuthController::class, 'logoutAuth']);

Route::post('/video-get', [VideoController::class, 'getVideo']);
Route::post('/video-save', [VideoController::class, 'save']);
Route::post('/video-destroy', [VideoController::class, 'destroy']);
Route::post('/video-update', [VideoController::class, 'update']);
Route::get('/dashboard-slider-fetch', [DashboardController::class, 'fetchSliders']);
Route::post('/dashboard-slider-update-status', [DashboardController::class, 'updateStatus']);
Route::post('/dashboard-slider-get', [DashboardController::class, 'getSlider']);
Route::post('/dashboard-slider-update', [DashboardController::class, 'update']);
Route::post('/band-fetch', [BandController::class, 'fetchBands']);
Route::get('/product-categories-fetch', [ShopController::class, 'fetchCategories']);
Route::get('/video-categories-fetch', [VideoController::class, 'fetchCategories']);
Route::post('/add-shop-category', [ShopController::class, 'addShopCategory']);
Route::post('/add-video-category', [VideoController::class, 'addVideoCategory']);
Route::post('/destroy-shop-category', [ShopController::class, 'destroyShopCategory']);
Route::post('/destroy-video-category', [VideoController::class, 'destroyVideoCategory']);
Route::get('/fetch-products', [ShopController::class, 'fetch']);
Route::post('/get-product', [ShopController::class, 'get']);
Route::post('/save-product', [ShopController::class, 'save']);
Route::post('/update-product', [ShopController::class, 'update']);
Route::post('/destroy-product', [ShopController::class, 'destroy']);
Route::post('/save-article', [ArticleController::class, 'save']);
Route::get('/article-fetch', [ArticleController::class, 'fetch']);
Route::post('/article-update', [ArticleController::class, 'update']);
Route::post('/article-destroy', [ArticleController::class, 'destroy']);
Route::get('/fetch-members', [MemberController::class, 'show']);
