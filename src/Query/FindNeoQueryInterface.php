<?php

namespace App\Query;

use App\Tools\Paginator\PaginatorControlElement;

interface FindNeoQueryInterface
{
    public function getId(): ?int;

    public function getNeoReferenceId(): ?int;

    public function getName(): ?string;

    public function getDateFrom(): ?string;

    public function getDateTo(): ?string;

    public function getSpeedFrom(): ?float;

    public function getSpeedTo(): ?float;

    public function getIsHazardous(): ?bool;

    public function getPaginatorControlElement(): PaginatorControlElement;
}