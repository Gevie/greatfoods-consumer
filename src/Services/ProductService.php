<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Services;

use GreatFoods\APIHandler\Contracts\Models\Product as ProductInterface;
use GreatFoods\APIHandler\Contracts\Services\ProductService as ProductServiceInterface;
use GreatFoods\APIHandler\Exceptions\NotFoundException;
use GreatFoods\APIHandler\Models\Product;

/**
 * ProductService Class
 *
 * @package GreatFoods\APIHandler\Services
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class ProductService extends AbstractService implements ProductServiceInterface
{
    /**
     * Gets products by menu id
     *
     * @param string $menuId The id of the menu to request
     *
     * @return array An array of the product models
     *
     * @throws NotFoundException If no products could be found
     */
    public function get(string $menuId): array
    {
        $url = sprintf('menu/%s/products', $menuId);
        $response = $this->call('GET', $url);

        if (empty($response['data'])) {
            throw new NotFoundException(sprintf('Could not find any products for menu "%s".', $menuId));
        }

        $products = [];
        foreach ($response['data'] as $product) {
            $products[] = new Product($product);
        }

        return $products;
    }

    /**
     * Updates a product for a given menu
     *
     * @param string $menuId The id of the menu to update
     * @param ProductInterface $product The product to update
     * @param array $data The new data to assign
     *
     * @return bool True on success, exception from call method on failure
     */
    public function update(string $menuId, ProductInterface $product, array $data): bool
    {
        $url = sprintf('menu/%s/product/%s', $menuId, $product->getId());

        // No info on the response so this is an unused variable
        $response = $this->call('PUT', $url, [
            'id' => $product->getId(),
            'name' => $data['name'] ?? $product->getName()
        ]);

        return true;
    }
}