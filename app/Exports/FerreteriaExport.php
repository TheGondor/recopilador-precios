<?php

namespace App\Exports;

use App\Exports\Sheets\FerreteriaPorCategoria;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FerreteriaExport implements WithMultipleSheets
{
    use Exportable;
    public $categories;
    public $provider;

    public function __construct($provider)
    {
        $this->provider = $provider;
        $this->categories = Product::where('convenio', 'Ferretería')->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) use ($provider){
                $query->on('providers.id', '=', 'product_providers.provider_id');
                $query->where('providers.id', $provider);
            });
        })->where('product_providers.price', '!=', 0)
        ->groupBy('products.category')
        ->get(['products.category']);
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach($this->categories as $category){
            $sheets[] = new FerreteriaPorCategoria($category->category, $this->provider);
        }

        return $sheets;
    }
}
