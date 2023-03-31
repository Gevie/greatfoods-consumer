<?php

namespace GreatFoods\APIHandler\Contracts\Services;

use GreatFoods\APIHandler\Contracts\Models\Product as ProductInterface;

interface ProductService
{
    public function get(string $menuId): array;

    public function update(string $menuId, ProductInterface $product, array $data): bool;
}