<?php

declare(strict_types=1);

namespace App\Entity\Util;

final readonly class SearchPagination
{
    public function __construct(
        public ?int $limit = null,
        public ?int $offset = null,
    ) {
    }
}
