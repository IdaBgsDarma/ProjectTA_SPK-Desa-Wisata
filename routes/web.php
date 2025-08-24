<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
require __DIR__.'/auth.php';
Route::get('/','App\Http\Controllers\FrontendController@index')->name('index');
Route::get('/kabupaten','App\Http\Controllers\FrontendController@kabupaten')->name('kabupaten');
Route::get('/kabupaten/{slug}','App\Http\Controllers\FrontendController@show_kabupaten')->name('show.kabupaten');
Route::get('/desa/{slug}','App\Http\Controllers\FrontendController@show_desa')->name('show.desa');
Route::get('/rekomendasi','App\Http\Controllers\FrontendController@rekomendasi_desa')->name('rekomendasi');
Route::group(['middleware' => ['auth'],'prefix' => 'admin'], function() {
    Route::get('/','App\Http\Controllers\Admin\DashboardController@index')->name('admin.index');

    Route::get('/akun','App\Http\Controllers\Admin\AkunController@index')->name('admin.user.index');
    Route::post('/akun','App\Http\Controllers\Admin\AkunController@store')->name('admin.user.store');
    Route::delete('/akun','App\Http\Controllers\Admin\AkunController@delete')->name('admin.user.delete');

    Route::get('/alternatif','App\Http\Controllers\Admin\AlternatifController@index')->name('admin.alternatif.index');
    Route::post('/alternatif','App\Http\Controllers\Admin\AlternatifController@store')->name('admin.alternatif.store');
    Route::patch('/alternatif','App\Http\Controllers\Admin\AlternatifController@update')->name('admin.alternatif.update');
    Route::delete('/alternatif','App\Http\Controllers\Admin\AlternatifController@delete')->name('admin.alternatif.delete');

    Route::get('/kriteria','App\Http\Controllers\Admin\KriteriaController@index')->name('admin.kriteria.index');
    Route::post('/kriteria','App\Http\Controllers\Admin\KriteriaController@store')->name('admin.kriteria.store');
    Route::patch('/kriteria','App\Http\Controllers\Admin\KriteriaController@update')->name('admin.kriteria.update');
    Route::delete('/kriteria','App\Http\Controllers\Admin\KriteriaController@delete')->name('admin.kriteria.delete');

    Route::get('/crips','App\Http\Controllers\Admin\CripsController@index')->name('admin.crips.index');
    Route::post('/crips','App\Http\Controllers\Admin\CripsController@store')->name('admin.crips.store');
    Route::patch('/crips','App\Http\Controllers\Admin\CripsController@update')->name('admin.crips.update');
    Route::delete('/crips','App\Http\Controllers\Admin\CripsController@delete')->name('admin.crips.delete');

    Route::get('/nilai','App\Http\Controllers\Admin\NilaiController@index')->name('admin.nilai.index');
    Route::post('/nilai','App\Http\Controllers\Admin\NilaiController@store')->name('admin.nilai.store');
    Route::patch('/nilai','App\Http\Controllers\Admin\NilaiController@update')->name('admin.nilai.update');
    Route::delete('/nilai','App\Http\Controllers\Admin\NilaiController@delete')->name('admin.nilai.delete');

    Route::get('/kabupaten','App\Http\Controllers\Admin\KabupatenController@index')->name('admin.kabupaten.index');
    Route::post('/kabupaten','App\Http\Controllers\Admin\KabupatenController@store')->name('admin.kabupaten.store');
    Route::patch('/kabupaten','App\Http\Controllers\Admin\KabupatenController@update')->name('admin.kabupaten.update');
    Route::delete('/kabupaten','App\Http\Controllers\Admin\KabupatenController@delete')->name('admin.kabupaten.delete');

    Route::get('/desa','App\Http\Controllers\Admin\DesaController@index')->name('admin.desa.index');
    Route::post('/desa','App\Http\Controllers\Admin\DesaController@store')->name('admin.desa.store');
    Route::patch('/desa','App\Http\Controllers\Admin\DesaController@update')->name('admin.desa.update');
    Route::delete('/desa','App\Http\Controllers\Admin\DesaController@delete')->name('admin.desa.delete');

    Route::get('/hasil','App\Http\Controllers\Admin\HasilController@index')->name('admin.hasil.index');
});
