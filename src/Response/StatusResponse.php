<?php

declare(strict_types=1);

namespace IPaySdk\Response;

use DateTimeInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;

class StatusResponse extends AbstractResponse
{
    #[SerializedName('pmt_id')]
    private int $paymentId;

    private string $cardMask;
    private int $invoice;
    private int $amount;

    #[SerializedName('desc')]
    private string $description;

    #[SerializedName('init_date')]
    private string|DateTimeInterface $initDate;

    #[SerializedName('bnk_error_group')]
    private ?int $bankErrorGroup;

    #[SerializedName('bnk_error_note')]
    private ?string $bankErrorNote;

    public function getPaymentId(): int
    {
        return $this->paymentId;
    }

    public function setPaymentId(int $paymentId): self
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    public function getCardMask(): string
    {
        return $this->cardMask;
    }

    public function setCardMask(string $cardMask): self
    {
        $this->cardMask = $cardMask;
        return $this;
    }

    public function getInvoice(): int
    {
        return $this->invoice;
    }

    public function setInvoice(int $invoice): self
    {
        $this->invoice = $invoice;
        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInitDate(): string|DateTimeInterface
    {
        return $this->initDate;
    }

    public function setInitDate(string|DateTimeInterface $initDate): self
    {
        $this->initDate = $initDate;

        return $this;
    }

    public function getBankErrorGroup(): ?int
    {
        return $this->bankErrorGroup;
    }

    public function setBankErrorGroup(?int $bankErrorGroup): self
    {
        $this->bankErrorGroup = $bankErrorGroup;

        return $this;
    }

    public function getBankErrorNote(): ?string
    {
        return $this->bankErrorNote;
    }

    public function setBankErrorNote(?string $bankErrorNote): self
    {
        $this->bankErrorNote = $bankErrorNote;

        return $this;
    }
}
