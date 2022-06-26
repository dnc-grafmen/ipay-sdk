<?php

declare(strict_types=1);

namespace IPaySdk\Service;

use IPaySdk\Model\ModelInterface;

interface ConverterServiceInterface
{
    public function convertModel(ModelInterface $model): array|string;
}
