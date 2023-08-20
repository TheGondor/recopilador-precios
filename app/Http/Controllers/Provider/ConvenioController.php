<?php

namespace App\Http\Controllers\Provider;

use App\Exports\AseoExport;
use App\Exports\AseoOfferProviderExport;
use App\Exports\ConvenioExport;
use App\Exports\ProviderConvenioExport;
use App\Exports\ConvenioFaltanteExport;
use App\Exports\ConvenioFaltanteLubeckExport;
use App\Exports\ConvenioLubeckExport;
use App\Exports\OfertaExport;
use App\Exports\OffersProvidersExport;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FerreteriaExport;


class ConvenioController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function summaryProvider(Provider $provider, $convenio)
    {
        if($convenio == 'ferreteria'){
            return (new FerreteriaExport($provider->id))->download($provider->name.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
        if($convenio == 'aseo'){
            return (new AseoExport($provider->id))->download($provider->name.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
        return Excel::download(new ProviderConvenioExport($convenio, $provider->id), $provider->name.'.xlsx');
    }

    public function summary($convenio)
    {

        return Excel::download(new ConvenioExport($convenio), $convenio.'.xlsx');
    }

    public function missing(Provider $provider, $convenio)
    {

        return Excel::download(new ConvenioFaltanteExport($convenio, $provider->id), $provider->name.'-faltante.xlsx');
    }

    public function offers(Provider $provider)
    {
        return Excel::download(new OfertaExport($provider->id), 'Ofertas '.$provider->name.'.xlsx');
    }

    public function offersProvider(Provider $provider, $convenio)
    {
        if($convenio == 'aseo'){
            return (new AseoOfferProviderExport($provider))->download($provider->name.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
        return Excel::download(new OffersProvidersExport($provider, $convenio), 'Ofertas Proveedores.xlsx');
    }

    public function faltanteLubeck(Provider $provider)
    {
        return Excel::download(new ConvenioFaltanteLubeckExport($provider->id), 'Venture-faltantes-lubeck.xlsx');
    }

    public function lubeck(Provider $provider)
    {
        return Excel::download(new ConvenioLubeckExport($provider->id), 'venture-lubeck.xlsx');
    }
}
