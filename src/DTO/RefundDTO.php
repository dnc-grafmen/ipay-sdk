<?php

declare(strict_types=1);

namespace IPaySdk\DTO;

final class RefundDTO extends AbstractDTO
{
    /**
     * @param int               $pid
     * @param int|null          $amount
     * @param array|string|null $info json|object
     */
    public function __construct(
        int $pid,
        private ?int $amount = null,
        private array|string|null $info = null
    ) {
        parent::__construct($pid);
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return array|string|null json|object
     */
    public function getInfo(): array|string|null
    {
        return $this->info;
    }
}
