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
    public function create(DataDTOInterface $data, int $merchantId, string $signKey): ModelInterface
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
