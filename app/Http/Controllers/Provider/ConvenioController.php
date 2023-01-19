<?php

namespace App\Http\Controllers\Provider;

use App\Exports\ConvenioExport;
use App\Exports\FerreteriaExport;
use App\Exports\OfertaExport;
use App\Exports\OffersProvidersExport;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use Maatwebsite\Excel\Facades\Excel;


class ConvenioController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function summary($convenio)
    {

        return Excel::download(new ConvenioExport($convenio), $convenio.'.xlsx');
    }

    public function offers(Provider $provider)
    {
        return Excel::download(new OfertaExport($provider->id), 'Ofertas '.$provider->name.'.xlsx');
    }

    public function offersProvider(Provider $provider)
    {


        return Excel::download(new OffersProvidersExport($provider), 'Ofertas Proveedores.xlsx');

    }
}
