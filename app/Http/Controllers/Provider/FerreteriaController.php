<?php

namespace App\Http\Controllers\Provider;

use App\Exports\FerreteriaExport;
use App\Exports\OfertaExport;
use App\Exports\OffersProvidersExport;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use Maatwebsite\Excel\Facades\Excel;


class FerreteriaController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function summary(Provider $provider)
    {

        return (new FerreteriaExport($provider->id))->download($provider->name.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
