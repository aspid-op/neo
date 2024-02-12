<?php

namespace App\Tools\Paginator;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator as OrmPaginator;

class Paginator
{
    private OrmPaginator $items;

    private PaginatorControlElement $controlElement;

    public function paginate(Query $query, PaginatorControlElement $paginatorControlElement): Paginator
    {
        $this->items = new OrmPaginator($query);
        $this->controlElement = $paginatorControlElement;

        $offset = $this->controlElement->getLimit() * ($this->controlElement->getPage() - 1);

        $this->items
            ->getQuery()
            ->setMaxResults($this->controlElement->getLimit())
            ->setFirstResult($offset)
        ;

        return $this;
    }

    public function getItems(): OrmPaginator
    {
        return $this->items;
    }

    public function getControlElement(): PaginatorControlElement
    {
        return $this->controlElement;
    }
}