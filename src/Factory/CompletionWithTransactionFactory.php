<?php

declare(strict_types=1);

namespace IPaySdk\Factory;

use IPaySdk\Actions;
use IPaySdk\DTO\CompletionWithTransactionDTO;
use IPaySdk\DTO\DataDTOInterface;
use IPaySdk\DTO\TransactionDTO;
use IPaySdk\Exceptions\PaymentException;
use IPaySdk\Model\ModelInterface;
use IPaySdk\Response\CompletionResponse;

final class CompletionWithTransactionFactory extends AbstractPaymentFactory
{
    use CreateTransactionsTrait {
        createTransaction as doCreateTransaction;
    }

    public function create(DataDTOInterface $data, int $merchantId, string $signKey): ModelInterface
    {
        assert($data instanceof CompletionWithTransactionDTO);

        return $this->factory->create('payment')
            ->addChild($this->createAuth($merchantId, $signKey))
            ->addChild($this->factory->create('action', Actions::COMPLETION))
            ->addChild($this->factory->create('pid', $data->getPid()))
            ->addChild($this->createTransactions($data->getTransactions()))
        ;
    }

    protected function createTransaction(TransactionDTO $transaction): ModelInterface
    {
        if (is_null($transaction->getLegalEntityId())) {
            throw PaymentException::fieldIsRequired('LegalEntityId [smch_id]');
        }

        return $this->doCreateTransaction($transaction);
    }

    public function getResponseType(): string
    {
        return CompletionResponse::class;
    }
}
