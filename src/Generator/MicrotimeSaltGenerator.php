<?php

declare(strict_types=1);

namespace IPaySdk\Generator;

final class MicrotimeSaltGenerator implements SaltGeneratorInterface
{
    public function getSalt(): string
    {
        return sha1(microtime(true));
    }
}
