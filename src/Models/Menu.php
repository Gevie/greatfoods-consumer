<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Models;

use GreatFoods\APIHandler\Contracts\Models\Menu as MenuInterface;

class Menu extends Model implements MenuInterface
{
    protected array $attributes = [
        'id' => null,
        'name' => null
    ];

    protected array $meta = [];

    public function getId(): string
    {
        return (string) $this->getAttribute('id');
    }

    public function getName(): string
    {
        return $this->getAttribute('name');
    }
}