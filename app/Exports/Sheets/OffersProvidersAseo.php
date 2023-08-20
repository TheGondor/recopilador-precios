<?php
namespace App\Exports\Sheets;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;

class OffersProvidersAseo implements FromView, WithTitle, ShouldAutoSize, WithStyles
{
    private $provider;
    private $count;
    private $wide;
    private $alphabet;
    private $region;

    public function __construct($provider, $region){
        $this->provider = $provider;
        $this->alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $this->region = $region;
    }

    public function view(): View
    {
        $provider = $this->provider;
        $region = $this->region;
        $offersProviders = Product::select([
            'providers.id',
            'providers.name',
            DB::raw('count(*) as offers')
        ])->where('convenio', 'aseo')->join('product_providers', function($query) use ($provider, $region){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->where('product_providers.special', '!=', 0);
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
            });
        })->whereIn('product_providers.status', [Product::STOCK, Product::STOCK_DISPERSION])
        ->where('product_providers.region', $region)
        ->groupBy(['providers.id', 'providers.name', 'providers.short_name'])
        ->orderBy('offers', 'desc')
        ->get();

        $this->wide = 3 + $offersProviders->count();

        $offersProduct = Product::select([
            'products.id',
            'products.name',
            'products.price',
            'products.product_id',
        ])->where('convenio', 'aseo')->join('product_providers', function($query) use ($provider, $region){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->where('product_providers.special', '!=', 0);
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
            });
        })->whereIn('product_providers.status', [Product::STOCK, Product::STOCK_DISPERSION])
        ->where('product_providers.region', $region)
        ->groupBy('products.id', 'products.name', 'products.price', 'products.product_id',)
        //->with('offersProviders')
        ->get();
        $this->count = $offersProduct->count();

        return view('exports.offersProvidersAseo', [
            'offersProviders' => $offersProviders,
            'offersProduct' => $offersProduct,
            'provider' => $provider,
            'region' => $region
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

    public function registerEventsNo(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                for($i = 2; $i <= $this->count + 1; $i++){
                    if($i % 2 == 0){
                        $event->sheet->getDelegate()->getStyle("A$i:".substr($this->alphabet, $this->wide, 1).$i)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('ACFAF6');
                    }
                }
                $event->sheet->getStyle('C')->getAlignment()->applyFromArray(
                    array('horizontal' => 'right')
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
