<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Mappers;

use GreatFoods\APIHandler\Models\Product;

class ProductMapper extends Mapper
{
    public function map(array $data): Product
    {
        return new Product($data);
    }
}