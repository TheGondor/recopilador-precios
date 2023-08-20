<?php
namespace App\Exports\Sheets;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class AseoPorRegion implements FromView, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    private $region;
    private $provider;
    private $count;

    public function __construct($region, $provider)
    {
        $this->region  = $region;
        $this->provider  = $provider;
    }

    public function view(): View
    {
        $provider = $this->provider;
        $products = Product::where('convenio', 'Aseo')->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.id', $provider);
            });
        })->where('product_providers.region', $this->region)
        ->get(['products.product_id', 'products.name', 'products.price','products.special','product_providers.price as provider_price','product_providers.region_id','product_providers.special as provider_special', 'product_providers.status as provider_status']);
        $this->count = Product::where('convenio', 'Aseo')->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.id', $provider);
            });
        })->where('product_providers.region', $this->region)
        ->count();

        return view('exports.aseo', [
            'products' => $products,
            'provider' => $this->provider
        ]);
    }

    public function title(): string
    {
        switch($this->region){
            case 1:
                return 'Region de Tarapaca';
            break;
            case 2:
                return 'Region de Antofagasta';
            break;
            case 3:
                return 'Region de Atacama';
            break;
            case 4:
                return 'Region de Coquimbo';
            break;
            case 5:
                return 'Region de Valparaiso';
            break;
            case 6:
                return 'Región del Libertador General Bernardo O\'Higgins';
            break;
            case 7:
                return 'Region del Maule';
            break;
            case 8:
                return 'Region del Biobío';
            break;
            case 9:
                return 'Region de la Araucania';
            break;
            case 10:
                return 'Region de los Lagos';
            break;
            case 11:
                return 'Región Aysén del General Carlos Ibáñez del Campo';
            break;
            case 12:
                return 'Region de Magallanes y Antartica Chilena';
            break;
            case 13:
                return 'Region Metropolitana';
            break;
            case 14:
                return 'Region de los Rios';
            break;
            case 15:
                return 'Region de Arica y Parinacota';
            break;
            case 16:
                return 'Region de Ñuble';
            break;
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                for($i = 2; $i <= $this->count + 1; $i++){
                    if($i % 2 == 0){
                        $event->sheet->getDelegate()->getStyle("A$i:H$i")
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('ACFAF6');
                    }
                }
                $event->sheet->getStyle('C')->getAlignment()->applyFromArray(
                    array('horizontal' => 'left')
                );
                $event->sheet->getStyle('D')->getAlignment()->applyFromArray(
                    array('horizontal' => 'right')
                );
                $event->sheet->getStyle('E')->getAlignment()->applyFromArray(
                    array('horizontal' => 'right')
                );
                $event->sheet->getStyle('F')->getAlignment()->applyFromArray(
                    array('horizontal' => 'right')
                );


            },
        ];
    }
}
