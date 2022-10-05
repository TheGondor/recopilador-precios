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

class OfertaExport implements FromView, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    private $provider;
    private $count;

    public function __construct($provider){
        $this->provider = $provider;
    }

    public function view(): View
    {
        $provider = $this->provider;
        $products = Product::where('convenio', 'Ferretería')->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.id', $provider);
            });
        })->where('product_providers.status', '=', Product::STOCK)
        ->where('product_providers.special', '!=', 0)
        ->get(['products.product_id', 'products.name', 'products.price','products.special','product_providers.price as provider_price', 'product_providers.special as provider_special', 'product_providers.status as provider_status']);
        $this->count = Product::where('convenio', 'Ferretería')->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.id', $provider);
            });
        })->where('product_providers.status', '=', Product::STOCK)
        ->where('product_providers.special', '!=', 0)
        ->count();
        return view('exports.ferreteria', [
            'products' => $products
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
                        $event->sheet->getDelegate()->getStyle("A$i:G$i")
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
