<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\TodayController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\VisitDetailController;

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


Route::group(['middleware' => 'auth'], function () {
	Route::get('/', [HomeController::class, 'home']);

	Route::group(['middleware' => 'admin'], function () {
		// Admin Routes
		Route::get('anasayfa', [HomeController::class, 'home']);
		Route::get('anasayfa/{slug}', [HomeController::class, 'home']);

		Route::get('yoneticiler', [AdminsController::class, 'list']);
		Route::get('yonetici/ekle', [AdminsController::class, 'create']);
		Route::get('yonetici/{slug}', [AdminsController::class, 'create']);
		Route::post('yonetici/kaydet', [AdminsController::class, 'store']);

		Route::get('urunler', [ProductsController::class, 'list']);
		Route::get('urun/ekle', [ProductsController::class, 'create']);
		Route::get('urun/{slug}', [ProductsController::class, 'create']);
		Route::post('urun/kaydet', [ProductsController::class, 'store']);

		Route::get('satis-noktalari', [StoresController::class, 'list']);
		Route::get('satis-noktasi/ekle', [StoresController::class, 'create']);
		Route::get('satis-noktasi/{slug}', [StoresController::class, 'create']);
		Route::post('satis-noktasi/kaydet', [StoresController::class, 'store']);

		Route::get('satis-temsilcileri', [EmployeesController::class, 'list']);
		Route::get('satis-temsilcisi/ekle', [EmployeesController::class, 'create']);
		Route::get('satis-temsilcisi/{slug}', [EmployeesController::class, 'create']);
		Route::post('satis-temsilcisi/kaydet', [EmployeesController::class, 'store']);

		Route::get('ziyaret/{id}/goruntule', [VisitDetailController::class, 'create']);

		Route::get('rapor/olustur', [ReportController::class, 'list']);
		Route::get('rapor/olustur/gun-sonu', [ReportController::class, 'day']);
		Route::get('rapor/olustur/satis-noktalari', [ReportController::class, 'stores']);
		Route::get('rapor/satis-noktalari', [ReportController::class, 'stores_pdf']);
	});

	Route::group(['middleware' => 'employee'], function () {
		// Employee Routes
		Route::get('bugun', [TodayController::class, 'create'])->name('today');

		Route::get('ziyaret/{route}', [VisitController::class, 'create']);
		Route::post('ziyaret/yeni', [VisitController::class, 'new']);
		Route::post('ziyaret/fotograf', [VisitController::class, 'image_store']);
		Route::post('ziyaret/kaydet', [VisitController::class, 'store']);
	});

	Route::get('/logout', [SessionsController::class, 'destroy']);
});

Route::group(['middleware' => 'guest'], function () {
	Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login', [SessionsController::class, 'create'])
		->name('login');
});
