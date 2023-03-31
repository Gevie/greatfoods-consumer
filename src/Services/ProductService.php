<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Services;

use GreatFoods\APIHandler\Contracts\Models\Product as ProductInterface;
use GreatFoods\APIHandler\Contracts\Services\ProductService as ProductServiceInterface;
use GreatFoods\APIHandler\Contracts\Services\Resolvers\TokenResolver as TokenResolverInterface;
use GreatFoods\APIHandler\Exceptions\NotFoundException;
use GreatFoods\APIHandler\Mappers\ProductMapper;
use GreatFoods\APIHandler\Models\Product;
use GuzzleHttp\ClientInterface;

class ProductService extends ApiService implements ProductServiceInterface
{
    public function __construct(
        protected ClientInterface $client,
        protected TokenResolverInterface $tokenResolver,
        protected string $url,
        protected ProductMapper $productMapper
    ) {
        // ...
    }

    public function get(string $menuId): array
    {
        $url = sprintf('menu/%s/products', $menuId);
        $response = $this->call('GET', $url);

        if (empty($response['data'])) {
            throw new NotFoundException(sprintf('Could not find any products for menu "%s".', $menuId));
        }

        return array_map(fn($productData) => $this->productMapper->map($productData), $response['data']);
    }

    public function update(string $menuId, ProductInterface $product, array $data): bool
    {
        $url = sprintf('menu/%s/product/%s', $menuId, $product->getId());

        // Quirk of the test, there is no info in the response so this is an unused variable
        $response = $this->call('PUT', $url, [
            'id' => $product->getId(),
            'name' => $data['name'] ?? $product->getName()
        ]);

        return true;
    }
}