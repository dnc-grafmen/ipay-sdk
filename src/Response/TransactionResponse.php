<?php

declare(strict_types=1);

namespace IPaySdk\Response;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TransactionResponse
{
    #[SerializedName('trn_id')]
    private int $trnId;

    #[SerializedName('smch_rr')]
    private int $smchRr;

    #[SerializedName('smch_mfo')]
    private int $smchMfo;

    #[SerializedName('smch_okpo')]
    private int $smchOkpo;

    #[SerializedName('smch_bank')]
    private string $smchBank;

    private int $invoice;
    private int $amount;

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

    public function setTrnId(int $trnId): self
    {
        $this->trnId = $trnId;

        return $this;
    }

    public function setSmchRr(int $smchRr): self
    {
        $this->smchRr = $smchRr;

        return $this;
    }

    public function setSmchMfo(int $smchMfo): self
    {
        $this->smchMfo = $smchMfo;

        return $this;
    }

    public function setSmchOkpo(int $smchOkpo): self
    {
        $this->smchOkpo = $smchOkpo;

        return $this;
    }

    public function setSmchBank(string $smchBank): self
    {
        $this->smchBank = $smchBank;

        return $this;
    }

    public function setInvoice(int $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
