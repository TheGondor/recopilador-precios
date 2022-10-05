<?php

namespace App\Http\Services\Provider;
use Symfony\Component\HttpClient\HttpClient;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Product_provider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Goutte\Client;

class StoreService
{
    private $goutteClient;

    public function __construct(){
        $httpClient = HttpClient::create(array(
            'timeout' => 5,
            'verify_peer' => false,
            'verify_host' => false
        ));
        $this->goutteClient = new Client($httpClient);

        $this->goutteClient->setServerParameter('accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9');
        $this->goutteClient->setServerParameter('accept-encoding', 'gzip, deflate, br');
        $this->goutteClient->setServerParameter('accept-language', 'es-ES,en-US;q=0.9,en;q=0.8');
        $this->goutteClient->setServerParameter('upgrade-insecure-requests', '1');
        $this->goutteClient->setServerParameter('user-agent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36');
        $this->goutteClient->setServerParameter('connection', 'keep-alive');

    }


}
