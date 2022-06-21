<?php

declare(strict_types=1);

namespace IPaySdk\Entity;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TransactionEntityResponse
{
    public function __construct(
//        #[SerializedName('trn_id')]
        private int $trnId,

//        #[SerializedName('smch_rr')]
        private int $smchRr,

//        #[SerializedName('smch_mfo')]
        private int $smchMfo,

//        #[SerializedName('smch_okpo')]
        private int $smchOkpo,

//        #[SerializedName('smch_bank')]
        private string $smchBank,

        private int $invoice,
        private int $amount,
    ) {}

    public function getTrnId(): int
    {
        return $this->trnId;
    }

    public function getSmchRr(): int
    {
        return $this->smchRr;
    }

    public function getSmchMfo(): int
    {
        return $this->smchMfo;
    }

    public function getSmchOkpo(): int
    {
        return $this->smchOkpo;
    }

    public function getSmchBank(): string
    {
        return $this->smchBank;
    }

    public function getInvoice(): int
    {
        return $this->invoice;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
