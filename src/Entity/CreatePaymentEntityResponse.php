<?php

declare(strict_types=1);

namespace IPaySdk\Entity;

class CreatePaymentEntityResponse extends AbstractEntityResponse
{
    private string $url;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
