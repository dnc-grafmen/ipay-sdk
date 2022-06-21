<?php

declare(strict_types=1);

namespace IPaySdk\Entity;

class CreatePaymentEntityResponse extends AbstractEntityResponse
{
    public function __construct(
        int $pid,
        int $status,
        string $salt,
        string $sign,
        private string $url,
    ) {
        parent::__construct($pid, $status, $salt, $sign);
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
