<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Mappers;

use GreatFoods\APIHandler\Models\Product;

class ProductMapper extends Mapper
{
    protected array $responseToAttribute = [
        'id' => 'id',
        'productName' => 'name'
    ];

    public function map(array $data): Product
    {
        $data = $this->assignAttributes($data);

        return new Product($data);
    }
}