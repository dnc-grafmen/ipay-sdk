<?php

declare(strict_types=1);

namespace IPaySdk\Generator;

interface SaltGeneratorInterface
{
    public function getSalt(): string;
}