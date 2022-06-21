<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\Actions;
use IPaySdk\DTO\CompletionDTO;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\Model\ModelInterface;

final class CompletionFactory extends AbstractPaymentFactory
{
    public function create(int $merchantId, string $signKey, DataDTOInterface $data): ModelInterface
    {
        assert($data instanceof CompletionDTO);

        return $this->factory->create('payment')
            ->addChild($this->createAuth($merchantId, $signKey))
            ->addChild($this->factory->create('action', Actions::COMPLETION))
            ->addChild($this->factory->create('pid', $data->getPid()));
    }
}
