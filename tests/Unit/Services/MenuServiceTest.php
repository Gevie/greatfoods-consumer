<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Tests\Unit\Services;

use GreatFoods\APIHandler\Contracts\Models\Menu as MenuInterface;
use GreatFoods\APIHandler\Contracts\Models\Product as ProductInterface;
use GreatFoods\APIHandler\Contracts\Services\Resolvers\TokenResolver as TokenResolverInterface;
use GreatFoods\APIHandler\Mappers\MenuMapper;
use GreatFoods\APIHandler\Models\Menu;
use GreatFoods\APIHandler\Models\Product;
use GreatFoods\APIHandler\Services\MenuService;
use GuzzleHttp\ClientInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class MenuServiceTest extends TestCase
{
    /**
     * @dataProvider menusProvider
     */
    public function testGetMenus(array $menus): void
    {
        // Arrange
        $client = Mockery::mock(ClientInterface::class);
        $tokenResolver = Mockery::mock(TokenResolverInterface::class);
        $baseUrl = 'https://www.greatfoods.test/api/v1';
        $menuMapper = Mockery::mock(MenuMapper::class);

        $service = new MenuService($client, $tokenResolver, $baseUrl, $menuMapper);

        $token = [
            'access_token' => 'mockedaccesstoken',
            'expires_in' => 999999999,
            'token_type' => 'Bearer',
            'scope' => 'catalogue'
        ];

        $tokenResolver->shouldReceive('getToken')
            ->withNoArgs()
            ->andReturn($token);


        $endpoint = sprintf('%s/menus', $baseUrl);
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
            ->andReturn(json_encode($menus, JSON_THROW_ON_ERROR));

        $mappedMenus = [
            new Menu([
                'id' => 1, 
                'name' => 'Starters',
                'products' => [
                    new Product([
                        'id' => 1,
                        'name' => 'Garlic Bread'
                    ]),
                    new Product([
                        'id' => 2,
                        'name' => 'Vegetable Samosa'
                    ])
                ]
            ]),
            new Menu([
                'id' => 2, 
                'name' => 'Mains',
                'products' => [
                    new Product([
                        'id' => 1,
                        'name' => 'Cheeseburger'
                    ])
                ]
            ]),
            new Menu([
                'id' => 3, 
                'name' => 'Takeaway', 
                'products' => []
            ]),
            new Menu([
                'id' => 4,
                'name' => 'Delivery'
            ]),
            new Menu([
                'id' => 5,
                'name' => 'Desserts',
                'products' => [
                    new Product([
                        'id' => 1,
                        'name' => 'Cheesecake'
                    ])
                ]
            ]),
        ];

        foreach ($menus['data'] as $key => $menu) {
            $menuMapper->shouldReceive('map')
                ->with($menu)
                ->andReturn($mappedMenus[$key]);
        }

        // Act
        $result = $service->get();

        // Assert
        $this->assertIsArray($result);
        foreach ($result as $key => $menu) {
            $this->assertInstanceOf(MenuInterface::class, $menu);
            $this->assertEquals($menu->getId(), (string) $menus['data'][$key]['id']);
            $this->assertEquals($menu->getName(), (string) $menus['data'][$key]['menuName']);
            $this->assertIsArray($menu->getProducts());

            foreach ($menu->getProducts() as $productKey => $product) {
                $this->assertTrue($product instanceof ProductInterface);
                $this->assertEquals($product->getId(), $menus['data'][$key]['menuProducts'][$productKey]['id']);
                $this->assertEquals($product->getName(), $menus['data'][$key]['menuProducts'][$productKey]['productName']);
            }
        }
    }

    public function menusProvider(): array
    {
        return [
            [
                'menus' => [
                    'data' => [
                        [
                            'id' => 1,
                            'menuName' => 'Starters',
                            'menuProducts' => [
                                [
                                    'id' => 1,
                                    'productName' => 'Garlic Bread'
                                ],
                                [
                                    'id' => 2,
                                    'productName' => 'Vegetable Samosa'
                                ]
                            ]
                        ],
                        [
                            'id' => 2,
                            'menuName' => 'Mains',
                            'menuProducts' => [
                                [
                                    'id' => 1,
                                    'productName' => 'Cheeseburger'
                                ]
                            ]
                        ],
                        [
                            'id' => 3,
                            'menuName' => 'Takeaway',
                            'menuProducts' => []
                        ],
                        [
                            'id' => 4,
                            'menuName' => 'Delivery'
                        ],
                        [
                            'id' => 5,
                            'menuName' => 'Desserts',
                            'menuProducts' => [
                                [
                                    'id' => 1,
                                    'productName' => 'Cheesecake'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ];
    }
}