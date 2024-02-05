<?php

declare(strict_types=1);

namespace CurrencyConverter;

use App\CurrencyConverter\Response\ConvertCurrencyResponse;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException as AssertInvalidArgumentException;

class ConvertCurrencyResponseTest extends TestCase
{
    public function testSuccessfulResponse(): void
    {
        $responseData = [
            'success' => true,
            'info' => ['rate' => 1.5],
            'result' => 150.0,
            'date' => '2024-01-01',
        ];

        $response = new ConvertCurrencyResponse($responseData);
        $this->assertEquals(new DateTimeImmutable('2024-01-01'), $response->conversionDate());
        $this->assertEquals(1.5, $response->rate());
        $this->assertEquals(150.0, $response->result());
        $this->assertTrue($response->isSuccess());

    }

    public function testResponseWithMissingKeys(): void
    {
        $this->expectException(AssertInvalidArgumentException::class);

        new ConvertCurrencyResponse(['success' => true]);
    }

    public function testResponseWithInvalidTypes(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ConvertCurrencyResponse([
            'success' => true,
            'info' => ['rate' => '12sdgfzew3'], // Invalid type
            'date' => 0, // Invalid type
            'result' => '!', // Invalid type
        ]);
    }

    public function testUnsuccessfulResponse(): void
    {
        $responseData = ['success' => false];

        $response = new ConvertCurrencyResponse($responseData);

        $this->assertFalse($response->isSuccess());
        $this->assertNull($response->rate());
        $this->assertNull($response->conversionDate());
        $this->assertNull($response->result());
    }
}
