<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\DTO\CreatePaymentDTO;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\DTO\UrlsDTO;
use IPaySdk\Model\ModelInterface;
use IPaySdk\Response\CreatePaymentResponse;
use IPaySdk\Utils;

final class CreatePaymentFactory extends AbstractPaymentFactory
{
    use CreateTransactionsTrait;

    public function create(DataDTOInterface $data, int $merchantId, string $signKey): ModelInterface
    {
        assert($data instanceof CreatePaymentDTO);

        $payment = $this->factory->create('payment')
            ->addChild($this->createAuth($merchantId, $signKey))
            ->addChild($this->createUrls($data->getUrls()))
            ->addChild($this->createTransactions($data->getTransactions()))
            ->addChild($this->factory->create('lifetime', $data->getLifetime()))
            ->addChild($this->factory->create('lang', $data->getLang()))
        ;

        if ($trademark = $data->getTrademark()) {
            $payment->addChild($this->factory->create('trademark', Utils::JsonEncode($trademark)));
        }

        return $payment;
    }

    private function createUrls(UrlsDTO $urls): ModelInterface
    {
        return $this->factory->create('urls')
            ->addChild($this->factory->create('good', $urls->getGoodUrl()))
            ->addChild($this->factory->create('bad', $urls->getBadUrl()))
        ;
    }

    public function getResponseType(): string
    {
        return CreatePaymentResponse::class;
    }
}
