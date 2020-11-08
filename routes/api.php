<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\test\TestController;
use App\Http\Controllers\Endpoints\ScoreController;

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

// Score Controller
Route::post("/score/test", [ScoreController::class, "test"]);
Route::post("/score/add", [ScoreController::class, "add"]);
Route::get("/score/all", [ScoreController::class, "getAllScores"]);
Route::get("/score/dailyScoreCount", [ScoreController::class, "getScoreSubmissionsByDate"]);

Route::post("/test", [TestController::class, "test"]);