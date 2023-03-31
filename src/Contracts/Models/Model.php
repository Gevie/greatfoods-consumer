<?php

namespace GreatFoods\APIHandler\Contracts\Models;

interface Model
{
    public function __get(string $key): void;

    public function __isset(string $key): bool;

    public function __set(string $key, mixed $value): void;

    public function __unset(string $key): void;

    public function getAllMetaData(): array;

    public function getAttribute(string $key): mixed;

    public function getAttributes(): array;

    public function getMetaData(string $key): mixed;
}