<?php

namespace App\Exports;

use App\Exports\Sheets\AseoPorRegion;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AseoExport implements WithMultipleSheets
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
            $sheets[] = new AseoPorRegion($i, $this->provider);
        }

        return $sheets;
    }
}
