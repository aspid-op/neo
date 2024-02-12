<?php

namespace App\Entity;

use App\Repository\NeoRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Table(name: 'neos')]
#[ORM\Entity(repositoryClass: NeoRepository::class)]
class Neo implements NeoInterface
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id, ORM\GeneratedValue()]
    private ?int $id;

    #[ORM\Column(type: 'date')]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    private DateTime $date;

    #[ORM\Column(type: 'integer', unique: true)]
    private int $neoReferenceId;

    #[ORM\Column(type: 'string', )]
    private string $name;

    #[ORM\Column(type: 'float')]
    private float $speed;

    #[ORM\Column(type: 'boolean')]
    private bool $isHazardous;

    public function __construct(
        ?int $id,
        DateTime $date,
        int $neoReferenceId,
        string $name,
        float $speed,
        bool $isHazardous,
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->neoReferenceId = $neoReferenceId;
        $this->name = $name;
        $this->speed = $speed;
        $this->isHazardous = $isHazardous;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getNeoReferenceId(): int
    {
        return $this->neoReferenceId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSpeed(): float
    {
        return $this->speed;
    }

    public function getIsHazardous(): bool
    {
        return $this->isHazardous;
    }
}
