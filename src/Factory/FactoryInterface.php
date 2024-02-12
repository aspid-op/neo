<?php

namespace App\Factory;

interface FactoryInterface
{
    public function create(array $arguments = []): mixed;
}
