<?php

declare(strict_types=1);

namespace IPaySdk\DTO;

class UrlsDTO
{
    public function __construct(
        private string $good,
        private string $bad,
    ) {}

    public function getGoodUrl(): string
    {
        return $this->good;
    }

    public function getBadUrl(): string
    {
        return $this->bad;
    }
}
