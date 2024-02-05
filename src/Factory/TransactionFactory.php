<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Account;
use App\Entity\Transaction;

final class TransactionFactory
{
    public static function create(
        Account $sender,
        Account $receiver,
        int     $amount,
        string  $currency,
        string  $type
    ): Transaction
    {
        return new Transaction(
            sender: $sender,
            receiver: $receiver,
            amount: $amount,
            currency: $currency,
            type: $type);
    }
}
