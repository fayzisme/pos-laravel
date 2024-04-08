<?php

use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('produk')->group( function(){
    Route::get('/list', [ProdukController::class, 'api'])->name('api.produk.list');
    Route::get('/list_active', [ProdukController::class, 'api_active'])->name('api.produk.list_active');
});

Route::prefix('transaksi')->group( function(){
    Route::get('/list', [TransactionController::class, 'api'])->name('api.transaksi.list');
});
