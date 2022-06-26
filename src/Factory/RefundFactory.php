<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\Actions;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\DTO\RefundDTO;
use IPaySdk\Model\ModelInterface;
use IPaySdk\Response\RefundResponse;
use IPaySdk\Utils;

final class RefundFactory extends AbstractPaymentFactory
{
    public function create(DataDTOInterface $data, int $merchantId, string $signKey): ModelInterface
    {
        assert($data instanceof RefundDTO);

        $payment = $this->factory->create('payment')
            ->addChild($this->createAuth($merchantId, $signKey))
            ->addChild($this->factory->create('action', Actions::REFUND))
            ->addChild($this->factory->create('pid', $data->getPid()));

        if ($amount = $data->getAmount()) {
            $payment->addChild($this->factory->create('amount', $amount));
        }

        if ($info = $data->getInfo()) {
            $payment->addChild($this->factory->create('info', Utils::JsonEncode($info)));
        }

        return $payment;
    }

    public function getResponseType(): string
    {
        return RefundResponse::class;
    }
}
