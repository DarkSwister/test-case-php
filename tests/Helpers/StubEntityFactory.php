<?php

namespace Helpers;

use App\Entity\Account;
use App\Entity\Client;

class StubEntityFactory
{
    public static function createClient(): Client
    {
        return new Client();
    }

    public static function createAccount(Client $client = null, string $currency = null, int $amount = 10000): Account
    {
        $client = $client ?? self::createClient();
        $currencies = ['USD', 'EUR', 'GBP'];
        $currency = $currency ?? $currencies[random_int(0, 2)];

        return new Account($client, $currency, $amount);
    }
}