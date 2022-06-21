<?php

declare(strict_types=1);

namespace IPaySdk;

final class Constants
{
    public const URL_LIVE = 'https://api.ipay.ua';
    public const URL_SANDBOX = 'https://sandbox-checkout.ipay.ua/api302';
    public const HTTP_METHOD = 'POST';

    public static function availableUrls(): array
    {
        return [self::URL_LIVE, self::URL_SANDBOX];
    }
}
