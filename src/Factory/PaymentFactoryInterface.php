<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\Model\ModelInterface;

interface PaymentFactoryInterface
{
    public function create(DataDTOInterface $data, int $merchantId, string $signKey): ModelInterface;

    public function getResponseType(): string;
}