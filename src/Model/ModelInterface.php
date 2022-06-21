<?php

declare(strict_types=1);

namespace IPaySdk\Model;

interface ModelInterface
{
    public function getName(): string;

    public function getValue(): int|string|null;

    public function getParent(): ?ModelInterface;

    public function setParent(?ModelInterface $parent): self;

    /** @return ModelInterface[] */
    public function getChildren(): array;

    public function addChild(ModelInterface $child): self;
}
