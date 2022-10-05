<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use GuzzleHttp\Client;
use App\Models\Provider;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;

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
            'updateProducts', 'updateFerreteria'
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

    public function updateProducts(Request $request)
    {
        if($request->auth == 'Upd4t3Pr0ducts'){
            $productos = json_decode($request->products);
            foreach($productos as $producto){
                $select = Product::where('id', $producto->id)->first();
                if($select){
                    $select->name = $producto->name;
                    $select->convenio = $producto->convenio;
                    $select->price = $producto->price;
                    $select->special = $producto->special;
                    $select->lubeck_price = $producto->lubeck_price;
                    $select->lubeck_status = $producto->lubeck_status;
                    $select->url = $producto->url;
                    $select->url_image = $producto->url_image;
                    $select->lubeck_special = $producto->lubeck_special;
                    $select->cataluna_price = $producto->cataluna_price;
                    $select->cataluna_status = $producto->cataluna_status;
                    $select->cataluna_special = $producto->cataluna_special;
                }
                else{
                    $select = new Product;
                    $select->product_id = $producto->id;
                    $select->name = $producto->name;
                    $select->convenio = $producto->convenio;
                    $select->price = $producto->price;
                    $select->special = $producto->special;
                    $select->lubeck_price = $producto->lubeck_price;
                    $select->lubeck_status = $producto->lubeck_status;
                    $select->url = $producto->url;
                    $select->url_image = $producto->url_image;
                    $select->lubeck_special = $producto->lubeck_special;
                    $select->cataluna_price = $producto->cataluna_price;
                    $select->cataluna_status = $producto->cataluna_status;
                    $select->cataluna_special = $producto->cataluna_special;
                }
                $select->save();
            }
        }

        return response()->json(['resultado' => 'Update Product Realizado']);
    }

    public function sendProducts()
    {
        $productos = Product::all();
        $auth = 'Upd4t3Pr0ducts';

        $client = new Client(['base_uri' => 'https://test.gusmudev.cl']);

        $cantidad = $productos->count();
        $i = 0;
        while($i <= $cantidad){
            $response = $client->request('POST', '/updateProducts', ['form_params' => [
                'products' => json_encode($productos->splice($i, 1000)),
                'auth' => $auth
            ],
            'verify'=>false]);

            echo '<br>'.$response->getBody();
            echo '<br>'.$i.' hasta '.$i+1000;
            $i = $i +1000;
        }


    }

    public function updateFerreteria(Request $request)
    {
        if($request->auth == 'Upd4t3Pr0ducts'){
            $productos = json_decode($request->products);
            foreach($productos as $producto){
                //return response()->json(['resultado' => $producto]);
                $select = Ferreteria::where('id', $producto->id)->first();
                if($select){
                    $select->name = $producto->name;
                    $select->category = $producto->category;
                    $select->price = $producto->price;
                    $select->special = $producto->special;
                    $select->lubeck_price = $producto->lubeck_price;
                    $select->lubeck_status = $producto->lubeck_status;
                    $select->cataluna_price = $producto->cataluna_price;
                    $select->cataluna_status = $producto->cataluna_status;
                    $select->url = $producto->url;
                    $select->url_image = $producto->url_image;
                    if($select->lubeck_special != 0 && $select->lubeck_special_date != NULL && $producto->lubeck_special == 0){
                        $select->lubeck_special_date = Carbon::now()->format('Y-m-d');
                    }
                    $select->lubeck_special = $producto->lubeck_special;
                    if($select->cataluna_special != 0 && $select->cataluna_special_date != NULL && $producto->cataluna_special == 0){
                        $select->cataluna_special_date = Carbon::now()->format('Y-m-d');
                    }
                    $select->cataluna_special = $producto->cataluna_special;
                }
                else{
                    $select = new Ferreteria;
                    $select->product_id = $producto->id;
                    $select->name = $producto->name;
                    $select->category = $producto->category;
                    $select->price = $producto->price;
                    $select->special = $producto->special;
                    $select->lubeck_price = $producto->lubeck_price;
                    $select->lubeck_status = $producto->lubeck_status;
                    $select->url = $producto->url;
                    $select->url_image = $producto->url_image;
                    $select->lubeck_special = $producto->lubeck_special;
                    $select->cataluna_price = $producto->cataluna_price;
                    $select->cataluna_status = $producto->cataluna_status;
                    $select->cataluna_special = $producto->cataluna_special;
                }
                $select->save();
            }
        }

        return response()->json(['resultado' => 'Update Ferreteria Realizado']);
    }

    public function sendFerreteria()
    {
        $productos = Ferreteria::all();
        $auth = 'Upd4t3Pr0ducts';

        $client = new Client(['base_uri' => 'https://test.gusmudev.cl']);

        $response = $client->request('POST', '/updateFerreteria', ['form_params' => [
            'products' => json_encode($productos),
            'auth' => $auth
        ],
        'verify'=>false]);

        echo $response->getBody();
    }

    public function providers()
    {
        $providers = Provider::all();
        return DataTables::of($providers)
            ->addColumn('name', function (Provider $provider) {
                return "<a href='/provider/$provider->id' target='_self'>$provider->name</a>";
            })
            ->rawColumns(['name'])
            ->toJson();
    }
}
