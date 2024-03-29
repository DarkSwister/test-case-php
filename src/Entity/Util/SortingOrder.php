<?php

declare(strict_types=1);

namespace App\Entity\Util;

final class SortingOrder
{
    public const string ASC = 'asc';
    public const string DESC = 'desc';
    /** @var string[] */
    public const array SORTING = [
        self::ASC,
        self::DESC,
    ];
}
