<?php

declare(strict_types=1);

namespace App\Entity\Util;

final readonly class SearchSorting
{
    public function __construct(
        public string $field,
        public string $order,
    ) {
    }
}
