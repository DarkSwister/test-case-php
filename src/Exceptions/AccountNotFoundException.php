<?php

namespace App\Exceptions;

namespace App\Domain\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(statusCode: Response::HTTP_NOT_FOUND)]
final class AccountNotFoundException extends RuntimeException
{
}
