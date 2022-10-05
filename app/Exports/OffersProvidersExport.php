<?php
namespace App\Exports;

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

class OffersProvidersExport implements FromView, WithTitle, WithStyles, WithEvents
{
    private $provider;
    private $count;
    private $wide;
    private $alphabet;

    public function __construct($provider){
        $this->provider = $provider;
        $this->alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }

    public function view(): View
    {
        $provider = $this->provider;
        $offersProviders = Product::select([
            'providers.id',
            'providers.name',
            DB::raw('count(*) as offers')
        ])->where('convenio', 'Ferretería')->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->where('product_providers.special', '!=', 0);
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
            });
        })->whereIn('product_providers.status', [Product::STOCK, Product::STOCK_DISPERSION])
        ->groupBy(['providers.id', 'providers.name', 'providers.short_name'])
        ->orderBy('offers', 'desc')
        ->get();

        $this->wide = 3 + $offersProviders->count();

        $offersProduct = Product::select([
            'products.id',
            'products.name',
            'products.price',
            'products.product_id',
        ])->where('convenio', 'Ferretería')->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->where('product_providers.special', '!=', 0);
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
            });
        })->whereIn('product_providers.status', [Product::STOCK, Product::STOCK_DISPERSION])
        ->groupBy('products.id', 'products.name', 'products.price', 'products.product_id',)
        //->with('offersProviders')
        ->first();
        dd($offersProduct->providerPrice($provider));
        $this->count = $offersProduct->count();

        return view('exports.offersProviders', [
            'offersProviders' => $offersProviders,
            'offersProduct' => $offersProduct,
            'provider' => $provider
        ]);
    }

    public function title(): string
    {
        return 'Ofertas';
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
