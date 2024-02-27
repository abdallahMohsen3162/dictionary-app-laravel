<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userApi;
use App\Http\Controllers\wordController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get("getusers", [userApi::class, 'getusers']);

Route::get("chechPassword", [userApi::class, 'chechPassword']);

Route::middleware('auth:sanctum')->get("usersInfo", [userApi::class, 'usersInfo']);

// Route::get("usersInfo", [userApi::class, 'usersInfo'])->middleware('auth:sanctum');

Route::post("login", [userApi::class, 'login']);

Route::post("register", [userApi::class, 'register']);

Route::post("createuser", [userApi::class, 'createuser']);

Route::middleware('auth:sanctum')->post("addWord", [wordController::class, 'addWord']);

// Route::post("addWord", [wordController::class, 'addWord']);

Route::get("word_test", [wordController::class, 'word_test']);

Route::get("GenerateQuiz", [wordController::class, 'GenerateQuiz']);

Route::middleware('auth:sanctum')->get("getAllwords", [wordController::class, 'getAllwords']);

Route::middleware('auth:sanctum')->delete("deleteWord", [wordController::class, 'deleteWord']);
