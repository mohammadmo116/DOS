<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/Books/{id}/buy', [OrderController::class,'store']);
Route::put('/Books/{id}/cost', [CatalogController::class,'update']);
Route::put('/Books/{id}/add', [CatalogController::class,'countI']);
Route::get('/Books/{id}', [CatalogController::class,'show']);
Route::get('/Books', [CatalogController::class,'index']);
Route::get('/search/{topic}', [CatalogController::class,'search']);

