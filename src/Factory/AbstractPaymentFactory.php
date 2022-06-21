<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\Generator\SaltGeneratorInterface;
use IPaySdk\Model\ModelInterface;

abstract class AbstractPaymentFactory implements PaymentFactoryInterface
{
    protected ModelFactory $factory;

    public function __construct(
        private SaltGeneratorInterface $saltGenerator,
    ) {
        $this->factory = new ModelFactory();
    }

    protected function createAuth(int $merchantId, string $signKey): ModelInterface
    {
        return $this->factory->create('auth')
            ->addChild($this->factory->create('mch_id', $merchantId))
            ->addChild($this->factory->create('salt', $salt = $this->saltGenerator->getSalt()))
            ->addChild($this->factory->create('sign', hash_hmac('sha512', $salt, $signKey)));
    }
}
