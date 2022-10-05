<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Visit_date;
use Yajra\DataTables\DataTables;
use App\Models\Provider;

class VisitasController extends Controller
{
    public function home(Provider $provider)
    {
        return view('provider.masVisitas', compact('provider'));
    }

    public function visitasList()
    {
        $products = Product::selectRaw('products.id, products.product_id, products.name, sum(visit_dates.quantity) as sum')->rightJoin('visit_dates', 'products.id','visit_dates.product_id')->groupBy(['products.id', 'products.product_id', 'products.name'])->get();

        return DataTables::of($products)
            ->addColumn('id', function (Product $producto) {
                return "
                    <a href='$producto->url' target='_blank'>$producto->product_id</a>";
            })
            ->addColumn('name', function (Product $producto) {
                return "
                    <a href='$producto->url' target='_blank'>$producto->name</a>";
            })
            ->addColumn('accion', function (Product $producto) {
                return "
                <a href='#' onclick='verVisita($producto->id)' class='btn btn-sm btn-outline-success m-1 px-3' title='Agregar'><i class='fas fa-chart-line'></i> Ver Grafico</a>";
            })
            ->rawColumns(['id', 'name', 'accion'])
            ->toJson();
    }

    public function modal(Request $request)
    {
        $id = $request->id;
        $idModal = $request->idModal;
        $product = Product::where("id", $id)->first();
        $products = Visit_date::where('product_id', $product->id)->orderBy('date', 'desc')->take(7)->get()->reverse();

        return view('provider.modal.visita', compact('idModal', 'product', 'products'));
    }
}
