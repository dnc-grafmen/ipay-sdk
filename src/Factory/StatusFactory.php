<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\Actions;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\DTO\StatusDTO;
use IPaySdk\Model\ModelInterface;
use IPaySdk\Response\StatusResponse;

final class StatusFactory extends AbstractPaymentFactory
{
    public function create(int $merchantId, string $signKey, DataDTOInterface $data): ModelInterface
    {
        assert($data instanceof StatusDTO);

        return $this->factory->create('payment')
            ->addChild($this->createAuth($merchantId, $signKey))
            ->addChild($this->factory->create('action', Actions::STATUS))
            ->addChild($this->factory->create('pid', $data->getPid()));
    }

    public function getResponseType(): string
    {
        return StatusResponse::class;
    }
}
