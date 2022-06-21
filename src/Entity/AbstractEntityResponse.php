<?php

declare(strict_types=1);

namespace IPaySdk\Entity;

abstract class AbstractEntityResponse implements EntityInterface
{
    public function __construct(
        private int $pid,
        private int $status,
        private string $salt,
        private string $sign,
    ){}

    public function getPid(): int
    {
        return $this->pid;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function getSign(): string
    {
        return $this->sign;
    }

}
