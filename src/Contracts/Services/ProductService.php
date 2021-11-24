<?php

namespace GreatFoods\APIHandler\Contracts\Services;

use GreatFoods\APIHandler\Contracts\Models\Product as ProductInterface;

/**
 * ProductService Interface
 *
 * @package GreatFoods\APIHandler\Contracts\Services
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface ProductService
{
    /**
     * Gets products by menu id
     *
     * @param string $menuId The id of the menu to request
     *
     * @return array An array of the product models
     */
    public function get(string $menuId): array;

    /**
     * Updates a product for a given menu
     *
     * @param string $menuId The id of the menu to update
     * @param ProductInterface $product The product to update
     * @param array $data The new data to assign
     *
     * @return bool True on success, exception from call method on failure
     */
    public function update(string $menuId, ProductInterface $product, array $data): bool;
}