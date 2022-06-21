<?php

declare(strict_types=1);

namespace IPaySdk\Entity;

class CompletionEntityResponse extends AbstractEntityResponse
{
    public function __construct(
        int $pid,
        int $status,
        string $salt,
        string $sign,

        private string $saleDate,

        /** @var TransactionEntityResponse[] $transactions */
        private array $transactions,
    ) {
        parent::__construct($pid, $status, $salt, $sign);
    }

    public function getSaleDate(): string
    {
        return $this->saleDate;
    }

    /**
     * @return TransactionEntityResponse[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }
}
