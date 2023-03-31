<?php

namespace GreatFoods\APIHandler\Contracts\Models;

interface KeyExistsChecks
{
    public function keyExists(string $key): bool;

    public function keyExistsInMetaData(string $key): bool;
}