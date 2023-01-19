<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use GuzzleHttp\Client;
use App\Models\Provider;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use App\Models\Product_provider;

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
            'updateProducts', 'updateFerreteria', 'addAseo'
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
            $id = '';
            $new_product = null;
            foreach($products as $product){
                if($new_product == null){
                    $new_product = Product::where('product_id', 'ID '.$request->id)->first();
                    if(!$new_product){
                        $new_product = new Product();
                    }
                    $new_product->name = $product['producto'];
                    $new_product->product_id = 'ID '.$product['id'];
                    $new_product->url = $request->url;
                    $new_product->url_image = $product['url_image'];
                    $new_product->price = str_replace(['$', '.', ','], '', $product['precio']);
                    $new_product->convenio  = 'Aseo';
                    $new_product->category  = 'Accesorios e implementos de aseo';
                    $new_product->save();
                }

                if(str_replace(['$', '.', ','], '', $product['precio']) < $new_product->price){
                    $new_product->price = str_replace(['$', '.', ','], '', $product['precio']);
                    $new_product->save();
                }

                $provider = Provider::where('name', $product['empresa'])->first();
                    if(!$provider){
                        $provider = Provider::create([
                            'name' => $product['empresa']
                        ]);
                    }
                Product_provider::updateOrCreate(
                    [
                        'product_id' => $new_product->id,
                        'provider_id' => $provider->id
                    ],
                    [
                        'price' => str_replace(['$', '.', ','], '', $product['precio']),
                        'special' => str_replace(['$', '.', ','], '', $product['oferta']),
                        'status' => $product['stock'] == 'stock' ? Product::STOCK : Product::NO_STOCK
                    ]
                );
                $id = $new_product->id;
            }
            return $id;
        }
    }
}
