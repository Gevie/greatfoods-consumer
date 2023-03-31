<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Mappers;

use GreatFoods\APIHandler\Models\Menu;
use GreatFoods\APIHandler\Mappers\ProductMapper;

class MenuMapper extends Mapper
{
    protected array $responseToAttribute = [
        'id' => 'id',
        'menuName' => 'name',
        'menuProducts' => 'products'
    ];

    public function __construct(protected ProductMapper $productMapper)
    {
        // ...
    }

    public function map(array $data): Menu
    {
        $data = $this->assignAttributes($data);
        
        if (! empty($data['products'])) {
            $data['products'] = array_map(
                fn($productData) => $this->productMapper->map($productData), $data['products']
            );
        }

        return new Menu($data);
    }
}