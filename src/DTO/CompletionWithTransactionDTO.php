<?php

declare(strict_types=1);

namespace IPaySdk\DTO;

final class CompletionWithTransactionDTO extends AbstractDTO
{
    public function __construct(
        int $pid,
        private TransactionDTOCollection $transactions,
    ) {
        parent::__construct($pid);
    }

    public function getTransactions(): TransactionDTOCollection
    {
        return $this->transactions;
    }
}
