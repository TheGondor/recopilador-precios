<?php

namespace App\Exports;

use App\Exports\Sheets\OfferProvidersWithRegion;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OfferProviderWithRegionsExport implements WithMultipleSheets
{
    use Exportable;
    public $provider;
    public $convenio;

    public function __construct($provider, $convenio)
    {
        $this->provider = $provider;
        $this->convenio = $convenio;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        for($i = 1; $i <= 16; $i++){
            $sheets[] = new OfferProvidersWithRegion($this->provider, $this->convenio, $i);
        }

        return $sheets;
    }
}
