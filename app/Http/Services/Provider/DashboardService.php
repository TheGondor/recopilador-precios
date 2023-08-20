<?php

namespace App\Http\Services\Provider;
use Yajra\DataTables\DataTables;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Product_provider;
use Illuminate\Support\Carbon;

class DashboardService
{
    public function pricesList(Request $request)
    {
        $products = Product::where('products.id', $request->id)->join('product_providers', function($query){
            $query->on('products.id', '=', 'product_providers.product_id');
            $query->join('providers', function($query) {
                $query->on('providers.id', '=', 'product_providers.provider_id');
            });
        })
        ->when($request->region != 0, function($query) use ($request){
            $query->where('product_providers.region', $request->region);
        })
        ->whereIn('product_providers.status', [Product::STOCK, Product::STOCK_DISPERSION])
        ->get(['providers.name', 'product_providers.price as provider_price', 'product_providers.special as provider_special']);
        return DataTables::of($products)
            ->addColumn('id', function (Product $product) {
                $id = substr($product->product_id, 2) ;
                return "
                    <a href='$product->url' target='_blank'>$id</a>";
            })
            ->addColumn('name', function (Product $product) {
                return $product->name;
            })
            ->addColumn('price', function (Product $product) {
                if($product->provider_special != 0 && $product->provider_special < $product->provider_price){
                    $normal = number_format( $product->provider_price, 0, "", ".");
                    $provider_special = number_format( $product->provider_special, 0, "", ".");
                    return "<span class='d-none'>$product->provider_special</span><span class='text-danger oferta'>$$provider_special</span><span class='text-decoration-line-through text-black-50'> $$normal</span>";
                }
                return "<span class='d-none'>$product->provider_price</span>".'$'.number_format( $product->provider_price, 0, "", ".");
            })
            ->addColumn('special', function (Product $product) {
                if($product->provider_special != 0){
                    $provider_special = number_format( $product->provider_special, 0, "", ".");
                    return "<span class='d-none'>$product->provider_special</span><span class='text-danger oferta'>$$provider_special</span>";
                }
                return "<span class='d-none'>$product->provider_price</span>".'Sin Oferta';
            })
            ->rawColumns(['name', 'price', 'special'])
            ->toJson();
    }

    public function modalPrice($id, $idModal, $region = 0)
    {
        $product = Product_provider::where("product_id", $id)->first();
        $hoy = Carbon::now()->format('Y-m-d');
        return view('provider.modal.prices', compact('idModal', 'product', 'hoy', 'region'));
    }
}
