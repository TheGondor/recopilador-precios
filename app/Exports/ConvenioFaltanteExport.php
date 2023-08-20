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

class ConvenioFaltanteExport implements FromView, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    public $convenio;
    public $provider;
    public $count;

    public function __construct($convenio, $provider)
    {
        $this->convenio = $convenio;
        $this->provider = $provider;
    }

    /**
     * @return array
     */
    public function view(): View
    {
        $provider = $this->provider;
        $products = Product::where('convenio', $this->convenio)->whereNotIn('id', function($query)use($provider){
            $query->select('product_providers.product_id')->from('product_providers')->where('product_providers.provider_id', $provider);
        })->get();
        $this->count = Product::where('convenio', $this->convenio)->whereNotIn('id', function($query)use($provider){
            $query->select('product_providers.product_id')->from('product_providers')->where('product_providers.provider_id', $provider);
        })->count();
        return view('exports.convenio', [
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
                        $event->sheet->getDelegate()->getStyle("A$i:D$i")
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
