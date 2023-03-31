<?php

namespace GreatFoods\APIHandler\Contracts\Models;

interface MetaDataAccessor
{
    public function getAllMetaData(): array;

    public function getMetaData(string $key): mixed;
}