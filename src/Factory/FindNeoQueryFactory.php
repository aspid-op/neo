<?php

namespace App\Factory;

use App\Query\FindNeoQuery;
use App\Query\FindNeoQueryInterface;
use App\Tools\Paginator\PaginatorControlElement;

class FindNeoQueryFactory implements FactoryInterface
{

    public function create(array $arguments = []): FindNeoQueryInterface
    {
        return new FindNeoQuery(
            $arguments['id'] ?? null,
            $arguments['neoReferenceId'] ?? null,
            $arguments['name'] ?? null,
            $arguments['dateFrom'] ?? null,
            $arguments['dateTo'] ?? null,
            $arguments['speedFrom'] ?? null,
            $arguments['speedTo'] ?? null,
            $arguments['isHazardous'] ?? null,
            new PaginatorControlElement(
                $arguments['page'] ?? null,
                $arguments['limit'] ?? null,
            ),
        );
    }
}
