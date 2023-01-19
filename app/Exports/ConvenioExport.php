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

class ConvenioExport implements FromView, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    public $convenio;
    public $count;

    public function __construct($convenio)
    {
        $this->convenio = $convenio;
    }

    /**
     * @return array
     */
    public function view(): View
    {
        $products = Product::where('convenio', $this->convenio)->get();
        $this->count = Product::where('convenio', $this->convenio)->count();
        return view('exports.convenio', [
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
                        $event->sheet->getDelegate()->getStyle("A$i:C$i")
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('ACFAF6');
                    }
                }
                $event->sheet->getStyle('C')->getAlignment()->applyFromArray(
                    array('horizontal' => 'right')
                );
            },
        ];
    }
}
