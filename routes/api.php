<?php

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnalysisController;

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

// ログイン中のユーザー情報
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 顧客検索機能
Route::middleware('auth:sanctum')->get('/searchCustomers', function (Request $request){
    return Customer::searchCustomers($request->search)->select('id', 'name', 'kana', 'tel')->paginate(50);
});

// データ分析
Route::middleware('auth:sanctum')->get('/analysis', [AnalysisController::class, 'index'])->name('api.analysis');