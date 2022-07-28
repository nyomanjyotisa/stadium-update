<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ShopeeController;
use App\Http\Controllers\TokopediaDpsController;

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
Route::post('/shopee/update_stok', [ShopeeController::class, 'update_stok']);
Route::post('/shopee/variasi_baru', [ShopeeController::class, 'variasi_baru']);
Route::post('/shopee/produk_baru', [ShopeeController::class, 'produk_baru']);

Route::post('/tokopedia/dps/update_stok', [TokopediaDpsController::class, 'update_stok']);
Route::post('/tokopedia/dps/variasi_baru', [TokopediaDpsController::class, 'variasi_baru']);
Route::post('/tokopedia/dps/produk_baru', [TokopediaDpsController::class, 'produk_baru']);


Route::get('/shopee/updatestok', function () {
    return view('shopee.update_stok');
});
Route::get('/shopee/variasibaru', function () {
    return view('shopee.variasi_baru');
});
Route::get('/shopee/produkbaru', function () {
    return view('shopee.produk_baru');
});

Route::get('/tokopedia/dps/updatestok', function () {
    return view('tokopedia.dps.update_stok');
});
Route::get('/tokopedia/dps/variasibaru', function () {
    return view('tokopedia.dps.variasi_baru');
});
Route::get('/tokopedia/dps/produkbaru', function () {
    return view('tokopedia.dps.produk_baru');
});

Route::get('/tokopedia/gyr/updatestok', function () {
    return view('tokopedia.gyr.update_stok');
});
Route::get('/tokopedia/gyr/variasibaru', function () {
    return view('tokopedia.gyr.variasi_baru');
});
Route::get('/tokopedia/gyr/produkbaru', function () {
    return view('tokopedia.gyr.produk_baru');
});
