<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Models;

use GreatFoods\APIHandler\Contracts\Models\Product as ProductInterface;

class Product extends Model implements ProductInterface
{
    protected array $attributes = [
        'id' => null,
        'name' => null
    ];
    
    public function getId(): string
    {
        return (string) $this->getAttribute('id');
    }

    public function getName(): string
    {
        return $this->getAttribute('name');
    }
}