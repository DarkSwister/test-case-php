<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(statusCode: Response::HTTP_BAD_REQUEST)]
final class NormalizationFailedException extends RuntimeException
{
}
