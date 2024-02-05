<?php

namespace App\Application\Factory;

use App\Entity\Account;
use App\Entity\Client;

final class AccountFactory
{
    public static function createUserAccount(Client $client, string $currency, int $balance): Account
    {
        return new Account($client, $currency, $balance);
    }
}