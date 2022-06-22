<?php

declare(strict_types=1);

namespace IPaySdk\Response;

class CompletionResponse extends AbstractResponse
{
    private string $saleDate;

    public TransactionsCollection $transactions;

    public function getSaleDate(): string
    {
        return $this->saleDate;
    }

    public function setSaleDate(string $saleDate): CompletionResponse
    {
        $this->saleDate = $saleDate;

        return $this;
    }

    public function getTransactions(): TransactionsCollection
    {
        return $this->transactions;
    }

    public function setTransactions(TransactionsCollection $transactions): CompletionResponse
    {
        $this->transactions = $transactions;

        return $this;
    }
}
