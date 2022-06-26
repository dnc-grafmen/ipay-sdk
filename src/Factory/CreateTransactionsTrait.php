<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\DTO\TransactionDTO;
use IPaySdk\DTO\TransactionDTOCollection;
use IPaySdk\Model\ModelInterface;
use IPaySdk\Utils;

trait CreateTransactionsTrait
{
    protected function createTransactions(TransactionDTOCollection $transactionCollection): ModelInterface
    {
        $transactions = $this->factory->create('transactions');

        foreach ($transactionCollection as $transaction) {
            $transactions->addChild($this->createTransaction($transaction));
        }

        return $transactions;
    }

    protected function createTransaction(TransactionDTO $transaction): ModelInterface
    {
        $transactionModel = $this->factory->create('transaction')
            ->addChild($this->factory->create('amount', $transaction->getAmount()))
            ->addChild($this->factory->create('currency', $transaction->getCurrency()))
            ->addChild($this->factory->create('desc', $transaction->getDescription()))
            ->addChild($this->factory->create('info', Utils::JsonEncode($transaction->getInfo())))
        ;

        if (!is_null($transaction->getLegalEntityId())) {
            $transactionModel->addChild(
                $this->factory->create('smch_id', $transaction->getLegalEntityId())
            );
        }

        return $transactionModel;
    }
}
