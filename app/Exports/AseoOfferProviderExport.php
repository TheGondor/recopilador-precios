<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\OffersProvidersAseo;

class AseoOfferProviderExport implements WithMultipleSheets
{
    use Exportable;
    public $provider;

    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        for($i = 1; $i <= 16; $i++){
            $sheets[] = new OffersProvidersAseo($this->provider, $i);
        }

        return $sheets;
    }
}
