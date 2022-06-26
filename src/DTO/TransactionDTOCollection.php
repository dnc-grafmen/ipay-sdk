<?php

declare(strict_types=1);

namespace IPaySdk\DTO;

use IteratorAggregate;

class TransactionDTOCollection implements IteratorAggregate
{
    /** @var TransactionDTO[] */
    public function __construct(private array $transactions = []) {}

    public function getIterator(): TransactionsIterator
    {
        return new TransactionsIterator($this);
    }

    public function getTransaction(int|string $position): ?TransactionDTO
    {
        return $this->transactions[$position] ?? null;
    }

    public function addTransaction(TransactionDTO $transaction): self
    {
        $this->transactions[] = $transaction;

        return $this;
    }
}
