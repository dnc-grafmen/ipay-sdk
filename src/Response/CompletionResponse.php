<?php

declare(strict_types=1);

namespace IPaySdk\Response;

use DateTimeInterface;

class CompletionResponse extends AbstractResponse
{
    private string|DateTimeInterface $saleDate;

    public TransactionsCollection $transactions;

    public function getSaleDate(): string|DateTimeInterface
    {
        return $this->saleDate;
    }

    public function setSaleDate(string|DateTimeInterface $saleDate): self
    {
        $this->saleDate = $saleDate;

        return $this;
    }

    public function getTransactions(): TransactionsCollection
    {
        return $this->transactions;
    }

    public function setTransactions(TransactionsCollection $transactions): self
    {
        $this->transactions = $transactions;

        return $this;
    }
}
