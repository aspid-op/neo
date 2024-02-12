<?php

namespace App\Entity;

use DateTime;

interface NeoInterface
{
    public function getId(): ?int;

    public function setId(int $id): void;

    public function getDate(): DateTime;

    public function getNeoReferenceId(): int;

    public function getName(): string;

    public function getSpeed(): float;

    public function getIsHazardous(): bool;
}