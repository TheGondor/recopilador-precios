<?php

namespace App\Http\Services;

use App\Models\Ferreteria;
//use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Product_provider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class ScrapperService
{
    private $goutteClient;

    public function __construct(){
        $httpClient = HttpClient::create(array(
            'timeout' => 5,
            'verify_peer' => false,
            'verify_host' => false
        ));
        $this->goutteClient = new Client($httpClient);
    }

    public array $startUrls = [
        'https://conveniomarco.mercadopublico.cl/aseo/aseo',
        'https://conveniomarco.mercadopublico.cl/mobiliario/mobiliario',
        'https://conveniomarco.mercadopublico.cl/escritorio/articulos-de-escritorio-y-oficina',
        'https://conveniomarco.mercadopublico.cl/alimentos/alimentos',
        'https://conveniomarco2.mercadopublico.cl/gas/gas',
        'https://conveniomarco2.mercadopublico.cl/vehiculos/vehiculos',
        'https://conveniomarco2.mercadopublico.cl/computadores202201/computadores',
        'https://conveniomarco2.mercadopublico.cl/insumos/dispositivo-e-insumos-medicos',
        'https://conveniomarco.mercadopublico.cl/emergencias202109/emergencia-y-prevencion'
    ];

    public function scrapping()
    {
        foreach($this->startUrls as $url){
            $this->calcularPaginas($url);
        }

    }

    public function calcularPaginas($url)
    {
        try{
            $crawler = $this->goutteClient->request('GET', $url);
            $totalItems = (int)$crawler->filter('.toolbar-number')->eq(1)->text();
            $totalPages = (int) round($totalItems / 12, 0);
            $convenio = ucfirst(mb_strtolower($crawler->filter('.base')->text()));;
            if($totalItems % 12 > 0){
                $totalPages++;
            }
            printf($convenio);
            //dump($totalPages);
            $this->scrappingPage($url, $totalPages, $convenio);
            //$this->scrappingPage($url, 2);
        }catch(\Exception $e){
            Log::channel('command')->error("Error al intentar scrappear $url");
            Log::channel('command')->error($e->getMessage());
           printf("Error al intentar scrappear $url");
        }

    }

    public function scrappingPage($url, $totalPages, $convenio)
    {
        Log::channel('command')->info("Iniciando scrapping de $url");
        printf("Iniciando scrapping de $url");
        for($i = 1; $i <= $totalPages; $i++){
            try{

                $fullUrl = $i == 1 ? $url : $url.'?p='.$i;
                $crawler = $this->goutteClient->request('GET', $fullUrl);
                //dump($fullUrl);

                $crawler->filter('.product-item')->each(function ($node) use ($convenio){
                    if($node->filter('.product-id-top')->count() > 0 && $node->filter('.base-price')->count() > 0 && $node->filter('.product-item-link')->count() > 0 && $node->filter('.cc-link')->count() > 0){
                        try{
                            $id = trim($node->filter('.product-id-top')->text());
                            $name = $node->filter('.product-item-link')->text();
                            $url = $node->filter('.cc-link')->attr('href');
                            $url_image = $node->filter('.product-image-photo')->attr('src');
                            $price = str_replace(['$', '.'], '', $node->filter('.base-price')->text());
                            $product = Product::updateOrCreate(
                                [
                                    'product_id' => $id
                                ],
                                [
                                    'name' => $name,
                                    'url' => $url,
                                    'url_image' => $url_image,
                                    'price' => $price,
                                    'convenio' => $convenio
                                ]
                            );
                        }
                        catch(\Exception $e){
                            Log::channel('command')->error($e->getMessage());
                            throw new \Exception("Error al guardar $name $id");
                        }
                    }

                });
            }
            catch(\Exception $e){
                Log::channel('command')->error($e->getMessage());
               printf($e->getMessage());
            }
        }

        Log::channel('command')->info("Scrapping de $url terminado.");
        printf("Scrapping de $url terminado.");
    }

    public function scrappingFerreteria()
    {
        $crawler = $this->goutteClient->request('GET', 'https://conveniomarco2.mercadopublico.cl/ferreteria/ferreteria');
        $crawler->filter('.filter-options-content .items .item a')->each(function ($node) {
            $this->calcularPaginasFerreteria($node->attr('href'));
        });


    }

    public function calcularPaginasFerreteria($url)
    {
        $crawler = $this->goutteClient->request('GET', $url);
        $totalItems = (int)$crawler->filter('.toolbar-number')->eq(1)->text();
        $totalPages = (int) round($totalItems / 12, 0);
        $category = ucfirst(mb_strtolower($crawler->filter('.base')->text()));;
        if($totalItems % 12 > 0){
            $totalPages++;
        }
        printf($category);
        //dump($totalPages);
        $this->scrappingPageFerreteria($url, $totalPages, $category);
        //$this->scrappingPage($url, 2);
    }

    public function scrappingPageFerreteria($url, $totalPages, $category)
    {
        Log::channel('command')->info("Iniciando scrapping de $url");
        printf("Iniciando scrapping de $url");
        for($i = 1; $i <= $totalPages; $i++){
            try{

                $fullUrl = $i == 1 ? $url : $url.'?p='.$i;
                $crawler = $this->goutteClient->request('GET', $fullUrl);
                //dump($fullUrl);

                $crawler->filter('.product-item')->each(function ($node) use ($category){
                    if($node->filter('.product-id-top')->count() > 0 && $node->filter('.base-price')->count() > 0 && $node->filter('.product-item-link')->count() > 0 && $node->filter('.cc-link')->count() > 0){
                        try{
                            $id = trim($node->filter('.product-id-top')->text());
                            $name = $node->filter('.product-item-link')->text();
                            $url = $node->filter('.cc-link')->attr('href');
                            $url_image = $node->filter('.product-image-photo')->attr('src');
                            $price = str_replace(['$', '.'], '', $node->filter('.base-price')->text());

                            $product = Product::updateOrCreate(
                                [
                                    'product_id' => $id
                                ],
                                [
                                    'name' => $name,
                                    'url' => $url,
                                    'url_image' => $url_image,
                                    'price' => $price,
                                    'convenio' => 'Ferretería',
                                    'category' => $category
                                ]
                            );
                            $product = Product::where('product_id', $id)->first();
                            $this->getProvidersPrices($url, $product);

                        }
                        catch(\Exception $e){
                            Log::channel('command')->error($e->getMessage());
                            throw new \Exception("Error al guardar $name $id");
                        }
                    }

                });
            }
            catch(\Exception $e){
                Log::channel('command')->error($e->getMessage());
               printf($e->getMessage());
            }
        }

        Log::channel('command')->info("Scrapping de $url terminado.");
        printf("Scrapping de $url terminado.");
    }

    public function getProvidersPrices($url, $product)
    {
        $crawler = $this->goutteClient->request('GET', $url);
        $price = 0;
        $special = 0;
        $crawler->filter('.wk-table-product-list tr')->each(function ($node) use (&$price,&$special){
            $proveedor = $node->filter('.wk-ap-checkbox-id input');
            if($proveedor->count() > 0){
                $provider_special = 0;
                $provider_price = 0;
                $provider_special = $node->filter('.special-price')->count() > 0 ? str_replace(['$', '.'], '', $node->filter('.special-price')->text()) : $provider_special;
                $provider_price = $node->filter('.base-price')->count() > 0 ? str_replace(['$', '.'], '', $node->filter('.base-price')->text()) : $provider_price;
                $price = $price == 0 && $provider_price != 0  && $node->attr('data-wkhqt') == '1' ? $provider_price : $price;
                $price = $price > $provider_price && $node->attr('data-wkhqt') == '1' ? $provider_price : $price;
                $special = ($special == 0 || $special > $provider_special) && $node->attr('data-wkhqt') == '1' && $provider_special != 0 ? $provider_special : $special;
            }
        });

        $product->price = $price;
        $product->special = $special;
        $product->save();
        $crawler->filter('.wk-table-product-list tr')->each(function ($node) use ($product){
            $proveedor = $node->filter('.wk-ap-checkbox-id input');
            if($proveedor->count() > 0){
                $provider_price = 0;
                $provider_special = 0;
                $status = 0;
                $special = 0;
                $name = $node->filter('.wk-ap-seller-name')->attr('data-name');
                //$this->error($proveedor);
                $provider = Provider::where('name', $name)->first();
                if(!$provider){
                    $provider = Provider::create([
                        'name' => $name
                    ]);
                }
                $provider_special = $node->filter('.special-price')->count() > 0 ? str_replace(['$', '.'], '', $node->filter('.special-price')->text()) : $provider_special;
                $provider_price = $node->filter('.base-price')->count() > 0 ? str_replace(['$', '.'], '', $node->filter('.base-price')->text()) : $provider_price;
                $status = $node->attr('data-wkhqt') == '1' ? Product::STOCK : Product::NO_STOCK;
                if($product->price * 1.4 < $provider_price && $product->price != 0 && $provider_price != 0){
                    $status = $status == Product::STOCK ? Product::STOCK_DISPERSION : Product::NO_STOCK_DISPERSION;
                }

                $product_provider = Product_provider::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'provider_id' => $provider->id
                    ],
                    [
                        'price' => $provider_price,
                        'special' => $provider_special,
                        'status' => $status
                    ]
                );
                if($product_provider->special != 0 && $product_provider->special_date != NULL && $provider_special == 0){
                    $product_provider->special_date = Carbon::now()->format('Y-m-d');
                    $product_provider->save();
                }
            }
        });
    }
}
