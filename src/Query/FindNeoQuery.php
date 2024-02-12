<?php

namespace App\Query;

use App\Tools\Paginator\PaginatorControlElement;
use Symfony\Component\Validator\Constraints as Assert;

class FindNeoQuery implements FindNeoQueryInterface
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $neoReferenceId,
        private readonly ?string $name,
        #[Assert\Date]
        private readonly ?string $dateFrom,
        #[Assert\Date]
        #[Assert\GreaterThanOrEqual(propertyPath: 'dateFrom')]
        private readonly ?string $dateTo,
        private readonly ?float $speedFrom,
        #[Assert\GreaterThanOrEqual(propertyPath: 'speedFrom')]
        private readonly ?float $speedTo,
        private readonly ?bool $isHazardous,
        #[Assert\Type(PaginatorControlElement::class)]
        private readonly PaginatorControlElement $paginatorControlElement,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNeoReferenceId(): ?int
    {
        return $this->neoReferenceId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDateFrom(): ?string
    {
        return $this->dateFrom;
    }

    public function getDateTo(): ?string
    {
        return $this->dateTo;
    }

    public function getSpeedFrom(): ?float
    {
        return $this->speedFrom;
    }

    public function getSpeedTo(): ?float
    {
        return $this->speedTo;
    }

    public function getIsHazardous(): ?bool
    {
        return $this->isHazardous;
    }

    public function getPaginatorControlElement(): PaginatorControlElement
    {
        return $this->paginatorControlElement;
    }
}