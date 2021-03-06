<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\Actions;
use IPaySdk\DTO\CompletionDTO;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\Model\ModelInterface;
use IPaySdk\Response\CompletionResponse;

final class CompletionFactory extends AbstractPaymentFactory
{
    public function create(DataDTOInterface $data, int $merchantId, string $signKey): ModelInterface
    {
        assert($data instanceof CompletionDTO);

        return $this->factory->create('payment')
            ->addChild($this->createAuth($merchantId, $signKey))
            ->addChild($this->factory->create('action', Actions::COMPLETION))
            ->addChild($this->factory->create('pid', $data->getPid()));
    }

    public function getResponseType(): string
    {
        return CompletionResponse::class;
    }
}
