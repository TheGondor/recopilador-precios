<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConvenioMarcoController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

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

Route::post('addAseo', [HomeController::class, 'addAseo']);

Route::get('excel/{convenio}', [App\Http\Controllers\Provider\ConvenioController::class, 'summary']);

Route::get('scrapping', [ConvenioMarcoController::class, 'scrapping']);

Route::get('resultados', [ConvenioMarcoController::class, 'search']);

Route::get('convenio/{product}', [ConvenioMarcoController::class, 'redirect']);

Auth::routes(['register' => true]);

Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'select']);

Route::post('providers/{convenio}', [App\Http\Controllers\HomeController::class, 'providers']);

Route::get('provider/{provider}/{convenio}', [App\Http\Controllers\Provider\DashboardController::class, 'home']);

Route::get('provider/{provider}/{convenio}/stock', [App\Http\Controllers\Provider\DashboardController::class, 'stock']);

Route::post('provider/{provider}/{convenio}/stockList', [App\Http\Controllers\Provider\DashboardController::class, 'stockList']);

Route::get('provider/{provider}/{convenio}/sin_stock', [App\Http\Controllers\Provider\DashboardController::class, 'sinStock']);

Route::post('provider/{provider}/{convenio}/sinStockList', [App\Http\Controllers\Provider\DashboardController::class, 'sinStockList']);

Route::get('provider/{provider}/{convenio}/stock_dispersion', [App\Http\Controllers\Provider\DashboardController::class, 'stockDispersion']);

Route::post('provider/{provider}/{convenio}/stockDispersionList', [App\Http\Controllers\Provider\DashboardController::class, 'stockDispersionList']);

Route::get('provider/{provider}/{convenio}/sin_stock_dispersion', [App\Http\Controllers\Provider\DashboardController::class, 'sinStockDispersion']);

Route::post('provider/{provider}/{convenio}/sinStockDispersionList', [App\Http\Controllers\Provider\DashboardController::class, 'sinStockDispersionList']);

Route::get('provider/{provider}/{convenio}/buscador', [App\Http\Controllers\Provider\BuscadorController::class, 'home']);

Route::get('provider/{provider}/{convenio}/resultados', [App\Http\Controllers\Provider\BuscadorController::class, 'search']);

Route::get('provider/{provider}/{convenio}/mas-buscados', [App\Http\Controllers\Provider\BuscadosController::class, 'home']);

Route::post('provider/{provider}/{convenio}/masBuscadosList', [App\Http\Controllers\Provider\BuscadosController::class, 'buscadosList']);

Route::post('provider/{provider}/{convenio}/modal-ver-busqueda', [App\Http\Controllers\Provider\BuscadosController::class, 'modal']);

Route::get('provider/{provider}/{convenio}/mas-visitas', [App\Http\Controllers\Provider\VisitasController::class, 'home']);

Route::post('provider/{provider}/{convenio}/masVisitasList', [App\Http\Controllers\Provider\VisitasController::class, 'visitasList']);

Route::post('provider/{provider}/{convenio}/modal-ver-visita', [App\Http\Controllers\Provider\VisitasController::class, 'modal']);

Route::get('provider/{provider}/{convenio}/ofertas', [App\Http\Controllers\Provider\DashboardController::class, 'oferta']);

Route::post('provider/{provider}/{convenio}/ofertaList', [App\Http\Controllers\Provider\DashboardController::class, 'ofertaList']);

Route::post('provider/{provider}/{convenio}/modal-form-fecha', [App\Http\Controllers\Provider\DashboardController::class, 'modalOferta']);

Route::post('provider/{provider}/{convenio}/oferta-fecha', [App\Http\Controllers\Provider\DashboardController::class, 'setFechaOferta']);

Route::get('provider/{provider}/{convenio}/ferreteria', [App\Http\Controllers\Provider\FerreteriaController::class, 'summary']);

Route::get('provider/{provider}/{convenio}/offers', [App\Http\Controllers\Provider\FerreteriaController::class, 'offers']);

Route::get('provider/{provider}/{convenio}/offersProviders', [App\Http\Controllers\Provider\FerreteriaController::class, 'offersProvider']);

Route::post('provider/{provider}/{convenio}/priceList', [App\Http\Controllers\Provider\DashboardController::class, 'pricesList']);

Route::post('provider/{provider}/{convenio}/modal-view-prices', [App\Http\Controllers\Provider\DashboardController::class, 'modalPrices']);

Route::post('updateProducts', [App\Http\Controllers\HomeController::class, 'updateProducts']);

Route::post('updateFerreteria', [App\Http\Controllers\HomeController::class, 'updateFerreteria']);

Route::get('sendProducts', [App\Http\Controllers\HomeController::class, 'sendProducts']);

Route::get('sendFerreteria', [App\Http\Controllers\HomeController::class, 'sendFerreteria']);
