<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BuscadorRequest;
use App\Models\Product;
use App\Models\Provider;

class BuscadorController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function home(Provider $provider)
    {
        return view('provider.buscador', compact('provider'));
    }

    public function search(BuscadorRequest $request, Provider $provider)
    {
        $search = mb_strtoupper($request->search);

        $searchs = explode(' ',$request->search);
        $resultados = Product::where(function ($query) use ($searchs) {
            $query->where(function ($query) use ($searchs){
                foreach($searchs as $search){
                    $query->orWhere('product_id', '=', "ID $search");
                }

            })
            ->orWhere(function ($query) use ($searchs){
                foreach($searchs as $search){
                    $query->where('name', 'like', "%$search%");
                }
            });
        })->where('convenio', 'Ferretería')->paginate(5);
        //dd($resultados);
        return view('provider.resultados', compact('resultados', 'search', 'provider'));
    }
}
