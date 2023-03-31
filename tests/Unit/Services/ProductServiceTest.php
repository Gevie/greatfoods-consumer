<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Tests\Unit\Services;

use GreatFoods\APIHandler\Contracts\Models\Product as ProductInterface;
use GreatFoods\APIHandler\Contracts\Services\Resolvers\TokenResolver as TokenResolverInterface;
use GreatFoods\APIHandler\Mappers\ProductMapper;
use GreatFoods\APIHandler\Models\Product;
use GreatFoods\APIHandler\Services\ProductService;
use GuzzleHttp\ClientInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * ProductServiceTest Class
 *
 * @package GreatFoods\APIHandler\Tests\Unit\Services
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class ProductServiceTest extends TestCase
{
    /**
     * @dataProvider productsProvider
     */
    public function testGet($products): void
    {
        // Arrange
        $client = Mockery::mock(ClientInterface::class);
        $tokenResolver = Mockery::mock(TokenResolverInterface::class);
        $baseUrl = 'https://www.greatfoods.test/api/v1';
        $productMapper = Mockery::mock(ProductMapper::class);

        $service = new ProductService($client, $tokenResolver, $baseUrl, $productMapper);

        $token = [
            'access_token' => 'mockedaccesstoken',
            'expires_in' => 999999999,
            'token_type' => 'Bearer',
            'scope' => 'catalogue'
        ];

        $tokenResolver->shouldReceive('getToken')
            ->withNoArgs()
            ->andReturn($token);


        $menuId = 1;
        $endpoint = sprintf('%s/menu/%s/products', $baseUrl, $menuId);
        $response = Mockery::mock(ResponseInterface::class);
        $client->shouldReceive('request')
            ->with('GET', $endpoint, [
                'json' => [],
                'headers' => [
                    'Authorization' => sprintf('%s %s', $token['token_type'], $token['access_token'])
                ]
            ])
            ->andReturn($response);

        $response->shouldReceive('getBody')
            ->withNoArgs()
            ->andReturn(json_encode($products, JSON_THROW_ON_ERROR));

        $mappedProducts = [
            new Product(['id' => 1, 'name' => 'Large Pizza']),
            new Product(['id' => 2, 'name' => 'Medium Pizza']),
            new Product(['id' => 3, 'name' => 'Burger']),
            new Product(['id' => 4, 'name' => 'Chips']),
            new Product(['id' => 5, 'name' => 'Soup']),
            new Product(['id' => 6, 'name' => 'Salad'])
        ];

        foreach($products['data'] as $key => $product) {
            $productMapper->shouldReceive('map')
                ->with($product)
                ->andReturn($mappedProducts[$key]);
        }

        // Act
        $result = $service->get((string) $menuId);

        // Assert
        $this->assertIsArray($result);
        foreach ($result as $key => $product) {
            $this->assertInstanceOf(ProductInterface::class, $product);
            $this->assertEquals($product->getId(), (string) $products['data'][$key]['id']);
            $this->assertEquals($product->getName(), (string) $products['data'][$key]['name']);
        }
    }

    public function testUpdate(): void
    {
        // Arrange
        $product = new Product([
            'id' => 1,
            'name' => 'Large Pizza'
        ]);

        $client = Mockery::mock(ClientInterface::class);
        $tokenResolver = Mockery::mock(TokenResolverInterface::class);
        $baseUrl = 'https://www.greatfoods.test/api/v1';
        $productMapper = Mockery::mock(ProductMapper::class);

        $service = new ProductService($client, $tokenResolver, $baseUrl, $productMapper);

        $token = [
            'access_token' => 'mockedaccesstoken',
            'expires_in' => 999999999,
            'token_type' => 'Bearer',
            'scope' => 'catalogue'
        ];

        $tokenResolver->shouldReceive('getToken')
            ->withNoArgs()
            ->andReturn($token);


        $menuId = 1;
        $data = [
            'id' => $product->getId(),
            'name' => 'Chips'
        ];

        $endpoint = sprintf('%s/menu/%s/product/%s', $baseUrl, $menuId, $product->getId());
        $response = Mockery::mock(ResponseInterface::class);
        $client->shouldReceive('request')
            ->with('PUT', $endpoint, [
                'json' => $data,
                'headers' => [
                    'Authorization' => sprintf('%s %s', $token['token_type'], $token['access_token'])
                ]
            ])
            ->andReturn($response);

        $response->shouldReceive('getBody')
            ->withNoArgs()
            ->andReturn(json_encode([], JSON_THROW_ON_ERROR));

        // Act
        $result = $service->update((string) $menuId, $product, $data);

        // Assert
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function productsProvider(): array
    {
        return [
            [
                'products' => [
                    'data' => [
                        [
                            'id' => 1,
                            'name' => 'Large Pizza'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Medium Pizza'
                        ],
                        [
                            'id' => 3,
                            'name' => 'Burger'
                        ],
                        [
                            'id' => 4,
                            'name' => 'Chips'
                        ],
                        [
                            'id' => 5,
                            'name' => 'Soup'
                        ],
                        [
                            'id' => 6,
                            'name' => 'Salad'
                        ]
                    ]
                ]
            ]
        ];
    }
}