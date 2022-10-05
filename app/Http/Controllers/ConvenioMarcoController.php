<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\BuscadorRequest;
use App\Models\Product;
use App\Models\Search;
use App\Models\Search_date;
use App\Models\Visit_date;
use Illuminate\Support\Carbon;



class ConvenioMarcoController extends Controller
{


    public function search(BuscadorRequest $request)
    {
        $search = mb_strtoupper($request->search);
        if(!$request->has('page')){
            $today = Carbon::now()->format('Y-m-d');
            $busqueda = Search::where('search', $search)->first();
            if($busqueda){
                $busquedaFecha = Search_date::where('search_id', $busqueda->id)->where('date', $today)->first();
                if($busquedaFecha){
                    $busquedaFecha->quantity++;
                    $busquedaFecha->save();
                }
                else{
                    $busquedaFecha = new Search_date;
                    $busquedaFecha->search_id = $busqueda->id;
                    $busquedaFecha->date = $today;
                    $busquedaFecha->quantity = 1;
                    $busquedaFecha->save();
                }
            }
            else{
                $busqueda = new Search;
                $busqueda->search = $search;
                $busqueda->save();

                $busquedaFecha = new Search_date;
                $busquedaFecha->search_id = $busqueda->id;
                $busquedaFecha->date = $today;
                $busquedaFecha->quantity = 1;
                $busquedaFecha->save();
            }
        }

        $searchs = explode(' ',$request->search);
        if($request->has('page')){
            $page = $request->page;
        }
        else{
            $page = 0;
        }

        if($request->has('order')){
            $order = $request->order;
        }
        else{
            $order = 'price_desc';
        }
        if($request->has('convenio')){
            $convenio = $request->convenio;
        }
        else{
            $convenio = 'todos';
        }
        $resultados = Product::where(function ($query) use ($searchs) {
            $query->where(function ($query) use ($searchs){
                foreach($searchs as $search){
                    $query->orWhere('product_id', '=', "ID $search");
                }

            })
            ->orWhere(function ($query) use ($searchs){
                foreach($searchs as $search){
                    $query->where(function($query) use ($search) {
                        $query->orWhere('name', 'like', "% $search %")->orWhere('name', 'like', "$search %")->orWhere('name', 'like', "% $search");
                    });
                }
            });
        })->when($order, function ($query) use ($order){
            if($order == 'price_desc'){
                $query->orderBy('price', 'desc');
            }
            if($order == 'price_asc'){
                $query->orderBy('price', 'asc');
            }
            if($order == 'name_asc'){
                $query->orderBy('name', 'asc');
            }
            if($order == 'name_desc'){
                $query->orderBy('name', 'desc');
            }
        })
        ->when($convenio, function ($query) use ($convenio){
            if($convenio != 'todos'){
                $query->where('convenio', $convenio);
            }
        })
        ->paginate(5);
        $convenios = Product::where(function ($query) use ($searchs) {
            $query->where(function ($query) use ($searchs){
                foreach($searchs as $search){
                    $query->orWhere('product_id', '=', "ID $search");
                }

            })
            ->orWhere(function ($query) use ($searchs){
                foreach($searchs as $search){
                    $query->where(function($query) use ($search) {
                        $query->orWhere('name', 'like', "% $search %")->orWhere('name', 'like', "$search %")->orWhere('name', 'like', "% $search");
                    });
                }
            });
        })
        ->groupBy('convenio')
        ->get(['convenio']);
        return view('resultados', compact('resultados', 'search', 'page', 'order', 'convenios', 'convenio'));
    }

    public function redirect(Request $request, Product $product){
        $today = Carbon::now()->format('Y-m-d');
        $visita = Visit_date::where('product_id', $product->id)->where('date', $today)->first();
        if($visita){
            $visita->quantity++;
            $visita->save();
        }
        else{
            $visita = new Visit_date;
            $visita->product_id = $product->id;
            $visita->date = $today;
            $visita->quantity = 1;
            $visita ->save();
        }
        return redirect($product->url);
    }
}
