<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Mappers;

abstract class Mapper
{
    protected array $responseToAttribute = [];

    abstract public function map(array $data): mixed;

    public function assignAttributes(array $data): array
    {
        foreach ($this->responseToAttribute as $responseKey => $attributeKey) {
            if (array_key_exists($responseKey, $data)) {
                $data[$attributeKey] = $data[$responseKey];
                unset($data[$responseKey]);
            }
        }

        return $data;
    }
}