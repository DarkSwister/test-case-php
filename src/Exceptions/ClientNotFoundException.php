<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(statusCode: Response::HTTP_NOT_FOUND)]
final class ClientNotFoundException extends RuntimeException
{
}
