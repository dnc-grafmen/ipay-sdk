<?php

declare(strict_types=1);

namespace IPaySdk\Entity;

abstract class AbstractEntityResponse implements EntityInterface
{
    private int $pid;
    private int $status;
    private string $salt;
    private string $sign;

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

    public function setPid(int $pid): self
    {
        $this->pid = $pid;

        return $this;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function setSign(string $sign): self
    {
        $this->sign = $sign;

        return $this;
    }
}
