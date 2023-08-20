<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Http\Services\Provider\DashboardService;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_provider;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function home(Provider $provider, $convenio)
    {
        $stock_dispersion = Product::where('convenio', $convenio)->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.name', $provider->name);
            });
        })->where('product_providers.status', Product::STOCK_DISPERSION)->count();
        $sin_stock_dispersion = Product::where('convenio', $convenio)->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.name', $provider->name);
            });
        })->where('product_providers.status', Product::NO_STOCK_DISPERSION)->count();
        $stock = Product::where('convenio', $convenio)->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.name', $provider->name);
            });
        })->where('product_providers.status', Product::STOCK)->count();
        $sin_stock = Product::where('convenio', $convenio)->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.name', $provider->name);
            });
        })->where('product_providers.status', Product::NO_STOCK)->count();
        return view('provider.home', compact('stock_dispersion', 'sin_stock_dispersion', 'stock', 'sin_stock', 'provider', 'convenio'));
    }

    public function stock(Provider $provider, $convenio)
    {
        return view('provider.stock', compact('provider', 'convenio'));
    }

    public function stockList(Provider $provider, $convenio)
    {
        $products = Product::where('convenio', $convenio)->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.name', $provider->name);
            });
        })->where('product_providers.status', Product::STOCK)
        ->get(['products.id', 'products.product_id', 'products.name', 'products.url', 'products.price','products.special','product_providers.price as provider_price', 'product_providers.special as provider_special', 'product_providers.region_id', 'product_providers.region']);
        return DataTables::of($products)
            ->addColumn('id', function (Product $product) {
                $id = $product->region_id == 0 ? substr($product->product_id, 2) : substr($product->region_id, 2).'-'.$product->region ;
                return "
                    <a href='$product->url' target='_blank'>$id</a>";
            })
            ->addColumn('name', function (Product $product) {
                return "
                    <a href='$product->url' target='_blank'>$product->name</a>";
            })
            ->addColumn('price', function (Product $product) {
                if($product->special != 0 && $product->special < $product->provider_special){
                    if($product->special <= $product->price){
                        $normal = number_format( $product->price, 0, "", ".");
                        $special = number_format( $product->special, 0, "", ".");
                        return "<span class='d-none'>$product->special</span><span class='text-danger oferta'>$$special</span><span class='text-decoration-line-through text-black-50'> $$normal</span>";
                    }
                    $normal = number_format( $product->price, 0, "", ".");
                    $special = number_format( $product->special, 0, "", ".");
                    return "<span class='d-none'>$product->normal</span><span class=''>$$normal</span><span class='text-danger oferta text-decoration-line-through'> $$special</span>";
                }
                return "<span class='d-none'>$product->price</span>".'$'.number_format( $product->price, 0, "", ".");
            })
            ->addColumn('provider_price', function (Product $product) {
                if($product->provider_special != 0){
                    $normal = number_format( $product->provider_price, 0, "", ".");
                    $provider_special = number_format( $product->provider_special, 0, "", ".");
                    return "<div onclick='modalPrice($product->id, $product->region)' role='button'><span class='d-none'>$product->provider_special</span><span class='text-danger oferta'>$$provider_special</span><span class='text-decoration-line-through text-black-50'> $$normal</span></div>";
                }
                $price = number_format( $product->provider_price, 0, "", ".");
                return "<div class='text-primary' role='button' onclick='modalPrice($product->id, $product->region)'><span class='d-none'>$product->provider_price</span>$$price</div>";
            })
            ->addColumn('ofertar', function (Product $product) {
                $min_price = $product->special <= $product->price && $product->special != 0? $product->special : $product->price;
                $min_price_provider = $product->provider_special <= $product->provider_price && $product->provider_special != 0 ? $product->provider_special : $product->provider_price;
                if($min_price < $min_price_provider && $min_price != 0){
                    $diferencia = $min_price_provider - $min_price;
                    $porcentaje = round($diferencia / $min_price_provider *100, 2);
                    $diferencia = number_format( $diferencia, 0, "", ".");
                    return "<span class='d-none'>$porcentaje</span>$porcentaje% ($$diferencia)";
                }
                return "<span class='d-none'>0</span>Proveedor es mas barato";
            })
            ->rawColumns(['id', 'name', 'price', 'provider_price','ofertar'])
            ->toJson();
    }

    public function sinStock(Provider $provider, $convenio)
    {
        return view('provider.sinStock', compact('provider', 'convenio'));
    }

    public function sinStockList(Provider $provider, $convenio)
    {
        $products = Product::where('convenio', $convenio)->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.name', $provider->name);
            });
        })->where('product_providers.status', Product::NO_STOCK)
        ->get(['products.id', 'products.product_id', 'products.name', 'products.url', 'products.price','products.special','product_providers.price as provider_price', 'product_providers.special as provider_special', 'product_providers.region_id', 'product_providers.region']);
        return DataTables::of($products)
            ->addColumn('id', function (Product $product) {
                $id = $product->region_id == 0 ? substr($product->product_id, 2) : substr($product->region_id, 2).'-'.$product->region ;
                return "
                    <a href='$product->url' target='_blank'>$id</a>";
            })
            ->addColumn('name', function (Product $product) {
                return "
                    <a href='$product->url' target='_blank'>$product->name</a>";
            })
            ->addColumn('price', function (Product $product) {
                if($product->special != 0 && $product->special < $product->provider_special){
                    $normal = number_format( $product->price, 0, "", ".");
                    $special = number_format( $product->special, 0, "", ".");
                    return "<span class='d-none'>$product->special</span><span class='text-danger oferta'>$$special</span><span class='text-decoration-line-through text-black-50'> $$normal</span>";
                }
                return "<span class='d-none'>$product->price</span>".'$'.number_format( $product->price, 0, "", ".");
            })
            ->addColumn('provider_price', function (Product $product) {
                if($product->lubeck_special != 0){
                    $normal = number_format( $product->provider_price, 0, "", ".");
                    $provider_special = number_format( $product->provider_special, 0, "", ".");
                    return "<div role='button' onclick='modalPrice($product->id, $product->region)'><span class='d-none'>$product->provider_special</span><span class='text-danger oferta'>$$provider_special</span><span class='text-decoration-line-through text-black-50'> $$normal</span></div>";
                }
                return "<div role='button' class='text-primary' onclick='modalPrice($product->id, $product->region)'><span class='d-none'>$product->provider_price</span>".'$'.number_format( $product->provider_price, 0, "", ".").'</div>';
            })
            ->rawColumns(['id', 'name', 'price', 'provider_price'])
            ->toJson();
    }

    public function stockDispersion(Provider $provider, $convenio)
    {
        return view('provider.stockDispersion', compact('provider', 'convenio'));
    }

    public function stockDispersionList(Provider $provider, $convenio)
    {
        $products = Product::where('convenio', $convenio)->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.name', $provider->name);
            });
        })->where('product_providers.status', Product::STOCK_DISPERSION)
        ->get(['products.product_id', 'products.name', 'products.url', 'products.price','products.special','product_providers.price as provider_price', 'product_providers.special as provider_special', 'product_providers.region_id', 'product_providers.region']);
        return DataTables::of($products)
            ->addColumn('id', function (Product $product) {
                $id = $product->region_id == 0 ? substr($product->product_id, 2) : substr($product->region_id, 2).'-'.$product->region ;
                return "
                    <a href='$product->url' target='_blank'>$id</a>";
            })
            ->addColumn('name', function (Product $product) {
                return "
                    <a href='$product->url' target='_blank'>$product->name</a>";
            })
            ->addColumn('price', function (Product $product) {
                $dispersion = number_format( (int) $product->price*1.4, 0, "", ".");
                return "<span class='d-none'>$product->price</span>".'$'.number_format( $product->price, 0, "", ".")." ($$dispersion)";
            })
            ->addColumn('provider_price', function (Product $product) {
                $dispersion = (int) $product->price*1.4;
                $diferencia = number_format( $product->provider_price - $dispersion, 0, "", ".");
                return "<span class='d-none'>$product->provider_price</span>".'$'.number_format( $product->provider_price, 0, "", ".")."<span class='text-danger oferta'> (+$$diferencia)</span>";
            })
            ->rawColumns(['id', 'name', 'price', 'provider_price'])
            ->toJson();
    }

    public function sinStockDispersion(Provider $provider, $convenio)
    {
        return view('provider.sinStockDispersion', compact('provider', 'convenio'));
    }

    public function sinStockDispersionList(Provider $provider, $convenio)
    {
        $products = Product::where('convenio', $convenio)->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.name', $provider->name);
            });
        })->where('product_providers.status', Product::NO_STOCK_DISPERSION)
        ->get(['products.product_id', 'products.name', 'products.url', 'products.price','products.special','product_providers.price as provider_price', 'product_providers.special as provider_special', 'product_providers.region_id', 'product_providers.region']);
        return DataTables::of($products)
            ->addColumn('id', function (Product $producto) {
                $id = substr($producto->product_id, 2) ;
                return "
                    <a href='$producto->url' target='_blank'>$id</a>";
            })
            ->addColumn('name', function (Product $producto) {
                return "
                    <a href='$producto->url' target='_blank'>$producto->name</a>";
            })
            ->addColumn('price', function (Product $producto) {
                $dispersion = number_format( (int) $producto->price*1.4, 0, "", ".");
                return "<span class='d-none'>$producto->price</span>".'$'.number_format( $producto->price, 0, "", ".")." ($$dispersion)";
            })
            ->addColumn('provider_price', function (Product $producto) {
                $dispersion = (int) $producto->price*1.4;
                $diferencia = number_format( $producto->provider_price - $dispersion, 0, "", ".");
                return "<span class='d-none'>$producto->provider_price</span>".'$'.number_format( $producto->provider_price, 0, "", ".")."<span class='text-danger oferta'> (+$$diferencia)</span>";
            })
            ->rawColumns(['id', 'name', 'price', 'provider_price'])
            ->toJson();
    }

    public function oferta(Provider $provider, $convenio)
    {
        return view('provider.oferta', compact('provider', 'convenio'));
    }

    public function ofertaList(Request $request, Provider $provider, $convenio)
    {
        $products = Product::where('convenio', $convenio)->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.name', $provider->name);
            });
        })->where('product_providers.status', Product::STOCK)
        ->where('product_providers.special', '!=', 0)
        ->get(['products.id', 'products.product_id', 'products.name', 'products.url', 'products.price','products.special','product_providers.price as provider_price', 'product_providers.special as provider_special', 'product_providers.special_date as special_date', 'product_providers.region_id', 'product_providers.region']);

        return DataTables::of($products)
            ->addColumn('id', function (Product $product) {
                $id = $product->region == 0 ? substr($product->product_id, 2) : substr($product->region_id, 2).'-'.$product->region ;
                return "
                    <a href='$product->url' target='_blank'>$id</a>";
            })
            ->addColumn('name', function (Product $product) {
                return "
                    <a href='$product->url' target='_blank'>$product->name</a>";
            })
            ->addColumn('price', function (Product $product) {
                if($product->special != 0 && $product->special < $product->provider_special){
                    $normal = number_format( $product->price, 0, "", ".");
                    $special = number_format( $product->special, 0, "", ".");
                    return "<span class='d-none'>$product->special</span><span class='text-danger oferta'>$$special</span><span class='text-decoration-line-through text-black-50'> $$normal</span>";
                }
                return "<span class='d-none'>$product->price</span>".'$'.number_format( $product->price, 0, "", ".");
            })
            ->addColumn('provider_price', function (Product $product) {
                if($product->provider_special != 0){
                    $normal = number_format( $product->provider_price, 0, "", ".");
                    $provider_special = number_format( $product->provider_special, 0, "", ".");
                    return "<span class='d-none'>$product->provider_special</span><span class='text-danger oferta'>$$provider_special</span><span class='text-decoration-line-through text-black-50'> $$normal</span>";
                }
                return "<span class='d-none'>$product->provider_price</span>".'$'.number_format( $product->provider_price, 0, "", ".");
            })
            ->addColumn('date', function (Product $product) use ($request) {
                if($product->special_date != NULL){
                    $hoy = Carbon::now();
                    $fecha = Carbon::createFromFormat('Y-m-d', $product->special_date);
                    $diferencia = $hoy->diffInDays($fecha);
                    if($diferencia == 0){
                        if($request->order[0]['column'] == 4 && $request->order[0]['dir'] == 'asc'){
                            $orden = $diferencia + 1000;
                        }
                        else{
                            $orden = $diferencia + 8;
                        }
                        return "<span class='d-none'>$orden</span><span class='text-danger oferta'>Vence hoy</span>";
                    }
                    if($fecha > $hoy){
                        if($request->order[0]['column'] == 4 && $request->order[0]['dir'] == 'asc'){
                            $orden = 1000 - $diferencia;
                        }
                        else{
                            $orden = $diferencia + 8;
                        }
                        $orden = $diferencia + 8;
                        return "<span class='d-none'>$orden</span>Vence en $diferencia dia(s)";
                    }
                    if($request->order[0]['column'] == 4 && $request->order[0]['dir'] == 'asc'){
                        $orden = $diferencia + 1000;
                    }
                    else{
                        $orden = 8 - $diferencia;
                    }
                    return "<span class='d-none'>$orden</span><span class='text-danger oferta'>Vencio hace $diferencia dia(s)</span>";
                }
                return "<span class='d-none'>0</span>No se ha ingresado fecha";
            })
            ->addColumn('date_order', function (Product $product) use ($request) {
                if($product->special_date != NULL){
                    $hoy = Carbon::now();
                    $fecha = Carbon::createFromFormat('Y-m-d', $product->special_date);
                    $diferencia = $hoy->copy()->diffInDays($fecha->copy());
                    if($request->order[0]['column'] == 5 && $request->order[0]['dir'] == 'asc'){
                        if($diferencia == 0){
                            return 8;
                        }
                        else if ($fecha < $hoy){
                            return 8 + $diferencia;
                        }
                        else{
                            return 8 - $diferencia;
                        }
                    }
                   else{
                       if($diferencia == 0){
                           return 1000;
                       }
                       else if ($fecha > $hoy){
                            return 1000 - $diferencia;
                        }
                        else{
                            return 1000 + $diferencia;
                        }
                   }
                }
                if($request->order[0]['column'] == 5 && $request->order[0]['dir'] == 'asc'){
                    $orden = 9999;
                }
                else{
                    $orden = 0;
                }
                return $orden;
            })
            ->addColumn('accion', function (Product $product) {
                return "
                <a href='#' onclick='setFecha($product->id)' class='btn btn-sm btn-outline-success m-1 px-3' title='Configurar Fecha'><i class='far fa-calendar-alt'></i> Fecha</a>";
            })
            ->rawColumns(['id', 'name', 'price', 'provider_price', 'date', 'date_order', 'accion'])
            ->toJson();
    }

    public function modalOferta(Request $request, Provider $provider, $convenio)
    {
        $id = $request->id;
        $idModal = $request->idModal;
        $producto = Product_provider::where("product_id", $id)->where("provider_id", $provider->id)->first();
        $hoy = Carbon::now()->format('Y-m-d');
        return view('provider.modal.fecha', compact('idModal', 'producto', 'hoy'));
    }

    public function setFechaOferta(Request $request)
    {
        $producto = Product_provider::where('id', $request->id)->first();
        if($producto){
            DB::beginTransaction();
            try {
                $producto->special_date = $request->date;
                $producto->save();
                DB::commit();
                $regreso = array();
                $regreso = Arr::add($regreso, 'estado', 'true');
                $regreso = Arr::add($regreso, 'mensaje', 'Datos guardados correctamente');
            } catch (\Exception $e) {
                DB::rollback();
                $regreso = array();
                $regreso = Arr::add($regreso, 'estado', 'false');
                $regreso = Arr::add($regreso, 'mensaje', 'Error al registrar los datos');
            }
        }
        else{
            DB::rollback();
                $regreso = array();
                $regreso = Arr::add($regreso, 'estado', 'false');
                $regreso = Arr::add($regreso, 'mensaje', 'El producto no fue encontrado');
        }

        return response()->json($regreso);
    }

    public function pricesList(Request $request){
        $service = new DashboardService;
        return $service->pricesList($request);
    }

    public function modalPrices(Request $request){
        $idModal = $request->idModal;
        $id = $request->id;
        $region = $request->region;
        $service = new DashboardService;
        return $service->modalPrice($id, $idModal, $region);
    }
}
