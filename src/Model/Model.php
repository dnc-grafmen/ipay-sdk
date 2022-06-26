<?php

declare(strict_types=1);

namespace IPaySdk\Model;

class Model implements ModelInterface
{
    private ?ModelInterface $parent = null;
    /** @var ModelInterface[] */
    private array $children = [];

    public function __construct(
        private string $name,
        private int|string|null $value = null,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): int|string|null
    {
        return $this->value;
    }

    public function getParent(): ?ModelInterface
    {
        return $this->parent;
    }

    public function setParent(?ModelInterface $parent): ModelInterface
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChild(ModelInterface $child): ModelInterface
    {
        $child->setParent($this);
        array_push($this->children, $child);

        return $this;
    }
}
