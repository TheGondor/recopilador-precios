<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use GuzzleHttp\Client;
use App\Models\Provider;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use App\Models\Product_provider;
use Exception;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'updateProducts', 'updateFerreteria', 'addAseo', 'addOficina'
        ]]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function select()
    {
        return view('select');
    }

    public function providers($convenio)
    {
        $providers = Provider::join('product_providers', function($query) use ($convenio){
            $query->on('providers.id', '=', 'product_providers.provider_id');
            $query->join('products', function($query) use ($convenio){
                $query->on('products.id', '=', 'product_providers.product_id');
                $query->where('products.convenio', $convenio);
            });
        })->groupBy(['providers.id', 'providers.name'])->get(['providers.id', 'providers.name']);
        return DataTables::of($providers)
            ->addColumn('name', function (Provider $provider) use ($convenio){
                return "<a href='/provider/$provider->id/$convenio' target='_self'>$provider->name</a>";
            })
            ->rawColumns(['name'])
            ->toJson();
    }

    public function addAseo(Request $request){
        if($request->password == 'pruebaDer83rnjer39$#*#$('){
            $products = json_decode($request->data, true);
            $new_product = Product::where('product_id', $request->product_id)->first();
            if(!$new_product){
                $new_product = new Product();
            }
            $new_product->name = $request->product_name;
            $new_product->product_id = $request->product_id;
            $new_product->url = $request->product_url;
            $new_product->url_image = $request->product_url_image;
            $new_product->price = str_replace(['$', '.', ','], '', $request->product_price);
            $new_product->special = str_replace(['$', '.', ','], '', $request->product_special);
            $new_product->convenio  = 'Aseo';
            $new_product->category  = 'Accesorios e implementos de aseo';
            $new_product->save();
            foreach($products as $product){
                if((str_replace(['$', '.', ','], '', $request->product_id) < $new_product->price && str_replace(['$', '.', ','], '', $product['price']) != 0) || $new_product->price == 0){
                    $new_product->price = str_replace(['$', '.', ','], '', $product['price']);
                    $new_product->save();
                }

                $provider = Provider::where('name', $product['providerName'])->first();
                    if(!$provider){
                        $provider = Provider::create([
                            'name' => $product['providerName']
                        ]);
                    }
                Product_provider::updateOrCreate(
                    [
                        'product_id' => $new_product->id,
                        'provider_id' => $provider->id,
                        'region' => $request->region,
                        'region_id' => $product['id'],
                    ],
                    [
                        'price' => str_replace(['$', '.', ','], '', $product['price']),
                        'special' => str_replace(['$', '.', ','], '', $product['special']),
                        'status' => $product['stock'] == 'stock' ? Product::STOCK : Product::NO_STOCK
                    ]
                );
            }
            return $new_product->product_id;
        }
    }

    public function addOficina(Request $request){
        try{
            if($request->password == 'pruebaDer83rnjer39$#*#$('){
                $products = json_decode($request->data, true);
                $new_product = Product::where('product_id', $request->product_id)->first();
                if(!$new_product){
                    $new_product = new Product();
                }
                $new_product->name = $request->product_name;
                $new_product->product_id = $request->product_id;
                $new_product->url = $request->product_url;
                $new_product->url_image = $request->product_url_image;
                $new_product->price = str_replace(['$', '.', ','], '', $request->product_price);
                $new_product->special = str_replace(['$', '.', ','], '', $request->product_special);
                $new_product->convenio  = 'Oficina';
                $new_product->category  = 'Oficina y Papeleria';
                $new_product->save();
                foreach($products as $product){
                    if((str_replace(['$', '.', ','], '', $request->product_id) < $new_product->price && str_replace(['$', '.', ','], '', $product['price']) != 0) || $new_product->price == 0){
                        $new_product->price = str_replace(['$', '.', ','], '', $product['price']);
                        $new_product->save();
                    }

                    $provider = Provider::where('name', $product['providerName'])->first();
                        if(!$provider){
                            $provider = Provider::create([
                                'name' => $product['providerName']
                            ]);
                        }
                    Product_provider::updateOrCreate(
                        [
                            'product_id' => $new_product->id,
                            'provider_id' => $provider->id,
                            'region' => $request->region,
                            'region_id' => $product['id'],
                        ],
                        [
                            'price' => str_replace(['$', '.', ','], '', $product['price']),
                            'special' => str_replace(['$', '.', ','], '', $product['special']),
                            'status' => $product['stock'] == 'stock' ? Product::STOCK : Product::NO_STOCK
                        ]
                    );
                }
                return $new_product->product_id;
            }

        } catch (Exception $e){
            return $e->getMessage();
        }
    }
}
