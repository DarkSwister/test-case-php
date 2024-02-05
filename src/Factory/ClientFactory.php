<?php

namespace App\Application\Factory;

use App\Entity\Client;

final class ClientFactory
{
    public static function create(): Client
    {
        return new Client();
    }
}