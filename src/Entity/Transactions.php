<?php

declare(strict_types=1);

namespace IPaySdk\Entity;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Transactions
{
    /**
     * @var TransactionEntityResponse[]
     * @SerializedName("transaction")
     */
    private array $transactions;

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function setTransactions(array $transactions): self
    {
        $this->transactions = $transactions;

        return $this;
    }

    public function addTransaction(TransactionEntityResponse $transaction): self
    {
        $this->transactions[] = $transaction;

        return $this;
    }
}
