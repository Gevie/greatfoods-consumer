<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Mappers;

abstract class Mapper
{
    abstract public function map(array $data): mixed;
}