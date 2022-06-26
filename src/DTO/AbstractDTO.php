<?php

declare(strict_types=1);

namespace IPaySdk\DTO;

abstract class AbstractDTO implements DataDTOInterface
{
    public function __construct(
        private int $pid,
    ) {}

    public function getPid(): int
    {
        return $this->pid;
    }
}
