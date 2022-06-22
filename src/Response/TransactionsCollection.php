<?php

declare(strict_types=1);

namespace IPaySdk\Response;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TransactionsCollection
{
    /**
     * @var TransactionResponse[]
     */
    #[SerializedName('transaction')]
    private array $items = [];

    public function all(): array
    {
        return $this->items;
    }

    public function setItems(array $items): TransactionsCollection
    {
        $this->items = $items;

        return $this;
    }

    public function add(TransactionResponse $transactionResponse): TransactionsCollection
    {
        $this->items[] = $transactionResponse;

        return $this;
    }
}
