<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\Model\ModelInterface;

interface PaymentFactoryInterface
{
    public function create(int $merchantId, string $signKey, DataDTOInterface $data): ModelInterface;
}