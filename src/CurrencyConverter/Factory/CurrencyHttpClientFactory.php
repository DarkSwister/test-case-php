<?php

namespace App\CurrencyConverter\Factory;

use App\CurrencyConverter\Client\CurrencyHttpClient;
use GuzzleHttp\Client as HttpClient;

final class CurrencyHttpClientFactory
{
    public function create(): CurrencyHttpClient
    {
        $client = new HttpClient([
            'base_uri' => $_ENV['EXCHANGE_RATE_SERVICE_URL'],
            'headers' => [
                'apikey' => $_ENV['EXCHANGE_RATE_SERVICE_API_KEY'],
            ],
        ]);
        return new CurrencyHttpClient($client);
    }
}