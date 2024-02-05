<?php

declare(strict_types=1);

namespace App\Entity\Util;

final class TransactionSearchCriteria
{
    public const string FIELD_CREATED_AT = 'created_at';

    /** @var string[] */
    public const array SORTING_FIELDS = [
        self::FIELD_CREATED_AT,
    ];

    public function __construct(
        public readonly ?int $accountId,
        public readonly ?SearchSorting $sorting = null,
        public readonly ?SearchPagination $pagination = null,
    ) {
    }
}
