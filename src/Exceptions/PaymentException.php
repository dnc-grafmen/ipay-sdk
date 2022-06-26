<?php

declare(strict_types=1);

namespace IPaySdk\Exceptions;

use DomainException;

class PaymentException extends DomainException
{
    public static function urlIsNotAllowed(string $url): self
    {
        return new self(sprintf('This url is not available. The received url is %s', $url), 500);
    }

    public static function fieldIsRequired(string $fieldName): self
    {
        return new self(sprintf('The %s field is required', $fieldName), 500);
    }

    public static function serviceNotExists(string $type): self
    {
        return new self(sprintf('Service %s doesnt exist', $type));
    }
}
