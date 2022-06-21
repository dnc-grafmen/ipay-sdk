<?php

declare(strict_types=1);

namespace IPaySdk\DTO;

final class ReversalDTO extends AbstractDTO
{
    /**
     * @param int          $pid
     * @param array|string $info json|object
     *
     */
    public function __construct(
        int $pid,
        private array|string $info
    ) {
        parent::__construct($pid);
    }

    /**
     * @return array|string json|object
     */
    public function getInfo(): array|string
    {
        return $this->info;
    }
}
