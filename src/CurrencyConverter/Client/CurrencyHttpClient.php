<?php

namespace App\CurrencyConverter\Client;

use App\CurrencyConverter\Exception\ConversionClientResponseFailException;
use App\CurrencyConverter\Response\ConvertCurrencyResponse;
use Throwable;
use GuzzleHttp\Client as HttpClient;
class CurrencyHttpClient
{
    public function __construct(private readonly HttpClient $httpClient)
    {
    }

    public function convert(string $base, string $target, string $amount): ConvertCurrencyResponse
    {
        try {
            $response = $this->httpClient->get('/exchangerates_data/convert', [
                'query' => [
                    'from' => $base,
                    'to' => $target,
                    'amount' => $amount,
                ],
            ]);
            return new ConvertCurrencyResponse(json_decode($response->getBody()->getContents(), true));
        } catch (Throwable $exception) {
            throw new ConversionClientResponseFailException($exception->getMessage());
        }
    }
}