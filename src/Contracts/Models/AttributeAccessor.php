<?php

namespace GreatFoods\APIHandler\Contracts\Models;

interface AttributeAccessor
{
    public function getAttribute(string $key): mixed;

    public function getAttributes(): array;
}