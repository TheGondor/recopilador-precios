<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Search;
use App\Models\Search_date;
use App\Models\Provider;

class BuscadosController extends Controller
{
    public function home(Provider $provider)
    {
        return view('provider.masBuscados', compact('provider'));
    }

    public function buscadosList()
    {
        $searches = Search::selectRaw('searchs.id, searchs.search, sum(search_dates.quantity) as sum')->join('search_dates', 'searchs.id','search_dates.search_id')->groupBy(['searchs.id', 'searchs.search'])->get();

        return DataTables::of($searches)
            ->addColumn('accion', function (Search $search) {
                return "
                <a href='#' onclick='verBusqueda($search->id)' class='btn btn-sm btn-outline-success m-1 px-3' title='Agregar'><i class='fas fa-chart-line'></i> Ver Grafico</a>";
            })
            ->rawColumns(['accion'])
            ->toJson();
    }

    public function modal(Request $request, Provider $provider)
    {
        $id = $request->id;
        $idModal = $request->idModal;
        $search = Search::where("id", $id)->first();
        $searches = Search_date::where('search_id', $search->id)->orderBy('date', 'desc')->take(7)->get()->reverse();

        return view('provider.modal.busqueda', compact('idModal', 'search', 'searches', 'provider'));
    }
}
