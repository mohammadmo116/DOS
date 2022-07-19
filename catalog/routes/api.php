<?php

use App\Http\Controllers\BookController;
use App\Models\Book;
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
Route::get('/getBooks', [BookController::class,'index']);

Route::get('/search', [BookController::class,'search']);

Route::get('/book/{book:id}', [BookController::class,'show']);

Route::put('/bookcost/{book:id}', [BookController::class,'update']);


Route::put('/addBook/{book:id}', [BookController::class,'countI']);


Route::put('/removeBook/{book:id}', [BookController::class,'countD']);
