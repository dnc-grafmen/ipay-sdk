<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\DTO\CreatePaymentDTO;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\DTO\UrlsDTO;
use IPaySdk\Model\ModelInterface;
use IPaySdk\Utils;

final class CreatePaymentFactory extends AbstractPaymentFactory
{
    use CreateTransactionsTrait;

    public function create(int $merchantId, string $signKey, DataDTOInterface $data): ModelInterface
    {
        assert($data instanceof CreatePaymentDTO);

        $payment = $this->factory->create('payment')
            ->addChild($this->createAuth($merchantId, $signKey))
            ->addChild($this->createUrls($data->getUrls()))
            ->addChild($this->createTransactions($data->getTransactions()))
            ->addChild($this->factory->create('lifetime', $data->getLifetime()))
            ->addChild($this->factory->create('lang', $data->getLang()))
        ;

        if (!is_null($data->getTrademark())) {
            $payment->addChild($this->factory->create('trademark', Utils::JsonEncode($data->getTrademark())));
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
}
