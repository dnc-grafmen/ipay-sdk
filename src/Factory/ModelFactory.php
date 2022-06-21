<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\Model\Model;
use IPaySdk\Model\ModelInterface;

class ModelFactory
{
    public function create(string $name, int|string|null $value = null): ModelInterface
    {
        return new Model($name, $value);
    }
}
