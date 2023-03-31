<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Tests\Assignment;

use GreatFoods\APIHandler\Contracts\Services\Resolvers\TokenResolver as TokenResolverInterface;
use GreatFoods\APIHandler\Mappers\MenuMapper;
use GreatFoods\APIHandler\Mappers\ProductMapper;
use GreatFoods\APIHandler\Models\Menu;
use GreatFoods\APIHandler\Models\Product;
use GreatFoods\APIHandler\Services\MenuService;
use GreatFoods\APIHandler\Services\ProductService;
use GuzzleHttp\ClientInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class AssignmentTest extends TestCase
{
    /**
     * @dataProvider scenarioOneProvider
     */
    public function testScenarioOne(array $menus, array $products): void
    {
        // Arrange
        $menuClient = Mockery::mock(ClientInterface::class);
        $productClient = Mockery::mock(ClientInterface::class);
        $tokenResolver = Mockery::mock(TokenResolverInterface::class);
        $baseUrl = 'https://www.greatfoods.test/api/v1';
        $menuMapper = Mockery::mock(MenuMapper::class);
        $productMapper = Mockery::mock(ProductMapper::class);

        $menuService = new MenuService($menuClient, $tokenResolver, $baseUrl, $menuMapper);
        $productService = new ProductService($productClient, $tokenResolver, $baseUrl, $productMapper);

        $token = [
            'access_token' => 'mockedaccesstoken',
            'expires_in' => 999999999,
            'token_type' => 'Bearer',
            'scope' => 'catalogue'
        ];

        $tokenResolver->shouldReceive('getToken')
            ->withNoArgs()
            ->andReturn($token);


        $menuEndpoint = sprintf('%s/menus', $baseUrl);
        $menuResponse = Mockery::mock(ResponseInterface::class);
        $menuClient->shouldReceive('request')
            ->with('GET', $menuEndpoint, [
                'json' => [],
                'headers' => [
                    'Authorization' => sprintf('%s %s', $token['token_type'], $token['access_token'])
                ]
            ])
            ->andReturn($menuResponse);

        $menuResponse->shouldReceive('getBody')
            ->withNoArgs()
            ->andReturn(json_encode($menus, JSON_THROW_ON_ERROR));

        $productEndpoint = sprintf('%s/menu/%s/products', $baseUrl, 3);
        $productResponse = Mockery::mock(ResponseInterface::class);
        $productClient->shouldReceive('request')
            ->with('GET', $productEndpoint, [
                'json' => [],
                'headers' => [
                    'Authorization' => sprintf('%s %s', $token['token_type'], $token['access_token'])
                ]
            ])
            ->andReturn($productResponse);

        $productResponse->shouldReceive('getBody')
            ->withNoArgs()
            ->andReturn(json_encode($products, JSON_THROW_ON_ERROR));

        $mappedMenus = [
            new Menu(['id' => 1, 'name' => 'Starters']),
            new Menu(['id' => 2, 'name' => 'Mains']),
            new Menu(['id' => 3, 'name' => 'Takeaway']),
            new Menu(['id' => 4, 'name' => 'Delivery']),
            new Menu(['id' => 5, 'name' => 'Desserts']),
        ];

        foreach ($menus['data'] as $key => $menu) {
            $menuMapper->shouldReceive('map')
                ->with($menu)
                ->andReturn($mappedMenus[$key]);
        }

        $mappedProducts = [
            new Product(['id' => 4, 'name' => 'Burger']),
            new Product(['id' => 5, 'name' => 'Chips']),
            new Product(['id' => 99, 'name' => 'Lasagne']),
        ];

        foreach($products['data'] as $key => $product) {
            $productMapper->shouldReceive('map')
                ->with($product)
                ->andReturn($mappedProducts[$key]);
        }

        // Act
        $menus = $menuService->get();

        $takeaway = null;
        foreach ($menus as $menu) {
            if ($menu->getName() === 'Takeaway') {
                $takeaway = $menu;
                break;
            }
        }

        if (! $takeaway) {
            $this->fail('Could not find a menu item named Takeaway');
        }

        $products = $productService->get($takeaway->getId());
        print("\n\nScenario One Result:\n--------------------\n\n");
        print("| ID | Name    |\n");
        print("| -- | ------- |\n");

        foreach ($products as $product) {
            $id = str_pad($product->getId(), 2, ' ', STR_PAD_RIGHT);
            $name = str_pad($product->getName(), 7, ' ', STR_PAD_RIGHT);

            print(sprintf("| %s | %s |\n", $id, $name));
        }

        $this->expectNotToPerformAssertions();
    }

    /**
     * @dataProvider scenarioTwoProvider
     */
    public function testScenarioTwo(array $product): void
    {
        $product = new Product($product);

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

        // When
        $result = $service->update((string) $menuId, $product, $data);

        // Then
        $this->assertIsBool($result);
        $this->assertTrue($result);

        print("\n\nScenario Two Result:\n--------------------\n");
        print("Product Updated Successfully.");
    }

    public function scenarioOneProvider(): array
    {
        return [
            [
                'menus' => [
                    'data' => [
                        [
                            'id' => 1,
                            'name' => 'Starters'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Mains'
                        ],
                        [
                            'id' => 3,
                            'name' => 'Takeaway'
                        ],
                        [
                            'id' => 4,
                            'name' => 'Delivery'
                        ],
                        [
                            'id' => 5,
                            'name' => 'Desserts'
                        ]
                    ]
                ],
                'products' => [
                    'data' => [
                        [
                            'id' => 4,
                            'name' => 'Burger'
                        ],
                        [
                            'id' => 5,
                            'name' => 'Chips'
                        ],
                        [
                            'id' => 99,
                            'name' => 'Lasagna'
                        ]
                    ]
                ]
            ]
        ];
    }

    public function scenarioTwoProvider(): array
    {
        return [
            [
                'broken_product' => [
                    'id' => 84,
                    'name' => 'Chpis'
                ]
            ]
        ];
    }
}