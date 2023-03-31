<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Mappers;

use GreatFoods\APIHandler\Models\Menu;
use GreatFoods\APIHandler\Mappers\ProductMapper;

class MenuMapper extends Mapper
{
    public function __construct(protected ProductMapper $productMapper)
    {
        // ...
    }

    public function map(array $data): Menu
    {
        if (! empty($data['products'])) {
            $data['products'] = array_map(
                fn($productData) => $this->productMapper->map($productData), $data['products']
            );
        }

        return new Menu($data);
    }
}