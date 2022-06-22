<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\Actions;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\DTO\ReversalDTO;
use IPaySdk\Model\ModelInterface;
use IPaySdk\Response\ReversalResponse;
use IPaySdk\Utils;

final class ReversalFactory extends AbstractPaymentFactory
{
    public function create(int $merchantId, string $signKey, DataDTOInterface $data): ModelInterface
    {
        assert($data instanceof ReversalDTO);

        return $this->factory->create('payment')
            ->addChild($this->createAuth($merchantId, $signKey))
            ->addChild($this->factory->create('action', Actions::REVERSAL))
            ->addChild($this->factory->create('pid', $data->getPid()))
            ->addChild($this->factory->create('info', Utils::JsonEncode($data->getInfo())))
        ;
    }

    public function getResponseType(): string
    {
        return ReversalResponse::class;
    }
}
