<?php

declare(strict_types=1);

namespace IPaySdk\DTO;

final class CreatePaymentDTO implements DataDTOInterface
{
    public function __construct(
        private UrlsDTO $urls,
        private TransactionDTOCollection $transactions,
        private int $lifetime,
        private string $lang,
        private array|string|null $trademark = null
    ) {}

    public function getUrls(): UrlsDTO
    {
        return $this->urls;
    }

    public function getTransactions(): TransactionDTOCollection
    {
        return $this->transactions;
    }

    public function getTrademark(): array|string|null
    {
        return $this->trademark;
    }

    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    public function getLang(): string
    {
        return $this->lang;
    }
}
