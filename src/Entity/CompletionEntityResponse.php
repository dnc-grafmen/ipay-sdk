<?php

declare(strict_types=1);

namespace IPaySdk\Entity;

class CompletionEntityResponse extends AbstractEntityResponse
{
    private string $saleDate;
    private Transactions $transactions;

    public function getSaleDate(): string
    {
        return $this->saleDate;
    }

    public function setSaleDate(string $saleDate): self
    {
        $this->saleDate = $saleDate;

        return $this;
    }

    public function getTransactions(): Transactions
    {
        return $this->transactions;
    }

    public function setTransactions(Transactions $transactions): self
    {
        $this->transactions = $transactions;

        return $this;
    }
}
