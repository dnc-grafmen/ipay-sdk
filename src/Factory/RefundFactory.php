<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\Actions;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\DTO\RefundDTO;
use IPaySdk\Model\ModelInterface;
use IPaySdk\Utils;

final class RefundFactory extends AbstractPaymentFactory
{
    public function create(int $merchantId, string $signKey, DataDTOInterface $data): ModelInterface
    {
        assert($data instanceof RefundDTO);

        $payment = $this->factory->create('payment')
            ->addChild($this->createAuth($merchantId, $signKey))
            ->addChild($this->factory->create('action', Actions::REFUND))
            ->addChild($this->factory->create('pid', $data->getPid()));

        if (!is_null($data->getAmount())) {
            $payment->addChild($this->factory->create('amount', $data->getAmount()));
        }

        if (!is_null($data->getInfo())) {
            $payment->addChild($this->factory->create('info', Utils::JsonEncode($data->getInfo())));
        }

        return $payment;
    }
}
