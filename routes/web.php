<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConvenioMarcoController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

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

Route::get('scrapping', [ConvenioMarcoController::class, 'scrapping']);

Route::get('resultados', [ConvenioMarcoController::class, 'search']);

Route::get('convenio/{product}', [ConvenioMarcoController::class, 'redirect']);

Auth::routes(['register' => true]);

Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'select']);

Route::post('providers', [App\Http\Controllers\HomeController::class, 'providers']);

Route::get('provider/{provider}', [App\Http\Controllers\Provider\DashboardController::class, 'home']);

Route::get('provider/{provider}/stock', [App\Http\Controllers\Provider\DashboardController::class, 'stock']);

Route::post('provider/{provider}/stockList', [App\Http\Controllers\Provider\DashboardController::class, 'stockList']);

Route::get('provider/{provider}/sin_stock', [App\Http\Controllers\Provider\DashboardController::class, 'sinStock']);

Route::post('provider/{provider}/sinStockList', [App\Http\Controllers\Provider\DashboardController::class, 'sinStockList']);

Route::get('provider/{provider}/stock_dispersion', [App\Http\Controllers\Provider\DashboardController::class, 'stockDispersion']);

Route::post('provider/{provider}/stockDispersionList', [App\Http\Controllers\Provider\DashboardController::class, 'stockDispersionList']);

Route::get('provider/{provider}/sin_stock_dispersion', [App\Http\Controllers\Provider\DashboardController::class, 'sinStockDispersion']);

Route::post('provider/{provider}/sinStockDispersionList', [App\Http\Controllers\Provider\DashboardController::class, 'sinStockDispersionList']);

Route::get('provider/{provider}/buscador', [App\Http\Controllers\Provider\BuscadorController::class, 'home']);

Route::get('provider/{provider}/resultados', [App\Http\Controllers\Provider\BuscadorController::class, 'search']);

Route::get('provider/{provider}/mas-buscados', [App\Http\Controllers\Provider\BuscadosController::class, 'home']);

Route::post('provider/{provider}/masBuscadosList', [App\Http\Controllers\Provider\BuscadosController::class, 'buscadosList']);

Route::post('provider/{provider}/modal-ver-busqueda', [App\Http\Controllers\Provider\BuscadosController::class, 'modal']);

Route::get('provider/{provider}/mas-visitas', [App\Http\Controllers\Provider\VisitasController::class, 'home']);

Route::post('provider/{provider}/masVisitasList', [App\Http\Controllers\Provider\VisitasController::class, 'visitasList']);

Route::post('provider/{provider}/modal-ver-visita', [App\Http\Controllers\Provider\VisitasController::class, 'modal']);

Route::get('provider/{provider}/ofertas', [App\Http\Controllers\Provider\DashboardController::class, 'oferta']);

Route::post('provider/{provider}/ofertaList', [App\Http\Controllers\Provider\DashboardController::class, 'ofertaList']);

Route::post('provider/{provider}/modal-form-fecha', [App\Http\Controllers\Provider\DashboardController::class, 'modalOferta']);

Route::post('provider/{provider}/oferta-fecha', [App\Http\Controllers\Provider\DashboardController::class, 'setFechaOferta']);

Route::get('provider/{provider}/ferreteria', [App\Http\Controllers\Provider\FerreteriaController::class, 'summary']);

Route::get('provider/{provider}/offers', [App\Http\Controllers\Provider\FerreteriaController::class, 'offers']);

Route::get('provider/{provider}/offersProviders', [App\Http\Controllers\Provider\FerreteriaController::class, 'offersProvider']);

Route::post('provider/{provider}/priceList', [App\Http\Controllers\Provider\DashboardController::class, 'pricesList']);

Route::post('provider/{provider}/modal-view-prices', [App\Http\Controllers\Provider\DashboardController::class, 'modalPrices']);

Route::post('updateProducts', [App\Http\Controllers\HomeController::class, 'updateProducts']);

Route::post('updateFerreteria', [App\Http\Controllers\HomeController::class, 'updateFerreteria']);

Route::get('sendProducts', [App\Http\Controllers\HomeController::class, 'sendProducts']);

Route::get('sendFerreteria', [App\Http\Controllers\HomeController::class, 'sendFerreteria']);
