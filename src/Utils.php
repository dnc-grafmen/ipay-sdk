<?php

declare(strict_types=1);

namespace IPaySdk;

final class Utils
{
    public static function JsonEncode(array|string $data): string
    {
        return is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;
    }
}
