<?php

namespace App\Tools\Paginator;

use Symfony\Component\Validator\Constraints as Assert;

class PaginatorControlElement
{
    public function __construct(
        #[Assert\Positive]
        private ?int $page,
        #[Assert\PositiveOrZero]
        private ?int $limit,
    ) {
        $this->page = $this->page ?? 1;
        $this->limit = $this->limit ?? 10;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}