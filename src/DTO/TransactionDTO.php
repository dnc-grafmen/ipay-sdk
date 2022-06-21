<?php

declare(strict_types=1);

namespace IPaySdk\DTO;

class TransactionDTO
{
    /**
     * @param int          $amount
     * @param string       $currency
     * @param string       $description
     * @param array|string $info          json|object
     * @param int|null     $legalEntityId smch_id
     */
    public function __construct(
        private int $amount,
        private string $currency,
        private string $description,
        private array|string $info,
        private ?int $legalEntityId,
    ) {}

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array|string json object
     */
    public function getInfo(): array|string
    {
        return $this->info;
    }

    /**
     * @return int|null smch_id
     */
    public function getLegalEntityId(): ?int
    {
        return $this->legalEntityId;
    }
}
