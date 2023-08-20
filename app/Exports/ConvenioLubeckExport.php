<?php

namespace App\Exports;

use App\Models\Product_provider;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class ConvenioLubeckExport implements FromView, WithTitle, ShouldAutoSize, WithStyles, WithEvents
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
        $products = Product_provider::from('product_providers as p3')->where('convenio', 'Ferreteria')->where('p3.provider_id', $this->provider)
        ->join('product_providers as p1', function($query){
            $query->on('p1.product_id', '=', 'p3.product_id');
            $query->where('p1.provider_id', 3);
        })
        ->join('products as p2', function($query){
            $query->on('p2.id', '=', 'p3.product_id');
        })->where('p3.provider_id', $this->provider)->get(['p2.product_id', 'p2.name', 'p2.price', 'p2.special', 'p3.price as provider_price', 'p3.special as provider_special', 'p1.price as lubeck_price', 'p1.special as lubeck_special']);

        $this->count = Product_provider::from('product_providers as p3')->where('convenio', 'Ferreteria')->where('p3.provider_id', $this->provider)
        ->join('product_providers as p1', function($query){
            $query->on('p1.product_id', '=', 'p3.product_id');
            $query->where('p1.provider_id', 3);
        })
        ->join('products as p2', function($query){
            $query->on('p2.id', '=', 'p3.product_id');
        })->where('p3.provider_id', $this->provider)->count();
        return view('exports.lubeck', [
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
                        $event->sheet->getDelegate()->getStyle("A$i:H$i")
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
