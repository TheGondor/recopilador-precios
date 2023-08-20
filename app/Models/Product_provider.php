<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_provider extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'price', 'special', 'special_date','status', 'provider_id', 'region', 'region_id'];

    public const STOCK = 1;
    public const NO_STOCK = 2;
    public const STOCK_DISPERSION = 3;
    public const NO_STOCK_DISPERSION = 4;
}
