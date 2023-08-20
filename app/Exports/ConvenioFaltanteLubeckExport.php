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

class ConvenioFaltanteLubeckExport implements FromView, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    public $provider;
    public $count;

    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return array
     */
    public function view(): View
    {
        $provider = $this->provider;
        $products = Product::where('convenio', 'Ferreteria')->whereNotIn('products.id', function($query)use($provider){
            $query->select('product_providers.product_id')->from('product_providers')->where('product_providers.provider_id', $provider);
        })->whereIn('products.id', function($query){
            $query->select('product_providers.product_id')->from('product_providers')->where('product_providers.provider_id', 3);
        })->join('product_providers', function($query){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) {
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.id', 3);
            });
        })->get(['products.product_id', 'products.price', 'products.special', 'product_providers.price as provider_price', 'product_providers.special as provider_special']);
        $this->count = Product::where('convenio', 'Ferreteria')->whereNotIn('products.id', function($query)use($provider){
            $query->select('product_providers.product_id')->from('product_providers')->where('product_providers.provider_id', $provider);
        })->whereIn('products.id', function($query){
            $query->select('product_providers.product_id')->from('product_providers')->where('product_providers.provider_id', 3);
        })->join('product_providers', function($query){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) {
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.id', 3);
            });
        })->get()->count();
        return view('exports.faltanteslubeck', [
            'products' => $products
        ]);
    }

    public function title(): string
    {
        return 'Productos faltantes';
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
                        $event->sheet->getDelegate()->getStyle("A$i:F$i")
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
            },
        ];
    }
}
