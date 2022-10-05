<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'product_id', 'price', 'special', 'url', 'url_image', 'convenio', 'category'];

    public const STOCK = 1;
    public const NO_STOCK = 2;
    public const STOCK_DISPERSION = 3;
    public const NO_STOCK_DISPERSION = 4;

    public function offersProviders()
    {
        return Product::select([
            'products.name as product',
            'products.id as productId',
            'providers.id',
            'providers.name',
            'product_providers.special',
        ])->join('product_providers', function($query) {
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->where('product_providers.special', '!=', 0);
            $query->join('providers', function($query){
                $query->on('providers.id', '=', 'product_providers.provider_id');
            });
        })->whereIn('product_providers.status', [Product::STOCK, Product::STOCK_DISPERSION])
        ->where('products.id', $this->id)
        ->get();
    }

    public function providerPrice($provider){
        $price = Product::select([
            'products.name as product',
            'products.id as productId',
            'product_providers.price',
        ])->join('product_providers', function($query) use ($provider){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->where('product_providers.provider_id', $provider->id);
        })
        ->where('products.id', $this->id)
        ->first();
        return $price ? $price->price : 0;
    }
}
