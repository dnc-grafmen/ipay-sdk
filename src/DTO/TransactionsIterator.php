<?php

declare(strict_types=1);

namespace IPaySdk\DTO;

use Iterator;

class TransactionsIterator implements Iterator
{
    private int $position = 0;

    public function __construct(private TransactionDTOCollection $collection) {}

    public function current(): TransactionDTO
    {
        return $this->collection->getTransaction($this->position);
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int|string
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return !is_null($this->collection->getTransaction($this->position));
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
