<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\Exceptions\PaymentException;
use IPaySdk\Service\ConverterServiceInterface;
use IPaySdk\Service\ConverterXmlService;

class ConverterServiceFactory
{
    public const TYPE_XML = 'xml';

    public function make(string $type): ConverterServiceInterface
    {
        return match ($type) {
            self::TYPE_XML => new ConverterXmlService(),
            default => throw PaymentException::serviceNotExists($type),
        };
    }
}
