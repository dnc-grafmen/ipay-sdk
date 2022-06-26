<?php

declare(strict_types=1);

namespace IPaySdk\Response;

use Symfony\Component\Serializer\Annotation\SerializedName;

abstract class AbstractResponse implements ApiResponseInterface
{
    #[SerializedName('pid')]
    private int $paymentId;
    private int $status;
    private string $salt;
    private string $sign;

    public function getPaymentId(): int
    {
        return $this->paymentId;
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

    public function setPaymentId(int $paymentId): self
    {
        $this->paymentId = $paymentId;

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
