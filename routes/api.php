<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\WarehousController;
use App\Http\Controllers\ProductMaterialController;
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

Route::get('product', [ProductController::class, 'index']);
Route::post('product', [ProductController::class, 'store']);
Route::get('product/{id}/materials', [ProductController::class, 'getMaterialsByProduct']);


Route::get('material', [MaterialController::class, 'index']);
Route::post('material', [MaterialController::class, 'store']);

Route::post('product-material', [ProductMaterialController::class, 'store']);

Route::get('warehous', [WarehousController::class, 'index']);
Route::post('warehous', [WarehousController::class, 'store']);
Route::post('warehous-order', [WarehousController::class, 'order']);
