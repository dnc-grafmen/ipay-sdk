<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\Constants;
use IPaySdk\IPayClient;
use IPaySdk\Validator\UrlValidator;

class IPayClientFactory
{
    public function create(
        int $merchantId,
        string $signKey,
        string $apiEndpoint = Constants::URL_LIVE,
    ): IPayClient {
        UrlValidator::validateAndThrowPaymentException($apiEndpoint);

        return new IPayClient(
            $merchantId,
            $signKey,
            $apiEndpoint,
        );
    }
}
