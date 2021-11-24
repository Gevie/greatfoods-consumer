<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Tests\Assignment;

use GreatFoods\APIHandler\Contracts\Models\Menu as MenuInterface;
use GreatFoods\APIHandler\Contracts\Services\Resolvers\TokenResolver as TokenResolverInterface;
use GreatFoods\APIHandler\Models\Product;
use GreatFoods\APIHandler\Services\MenuService;
use GreatFoods\APIHandler\Services\ProductService;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * AssignmentTest Class
 *
 * @package GreatFoods\APIHandler\Tests\Assignment
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class AssignmentTest extends TestCase
{
    /**
     * Run the first scenario as a test
     *
     * @dataProvider scenarioOneProvider
     * @param array $menus The menus
     * @param array $products The products
     *
     * @return void
     */
    public function testScenarioOne(array $menus, array $products): void
    {
        // Give
        $menuClient = \Mockery::mock(ClientInterface::class);
        $productClient = \Mockery::mock(ClientInterface::class);
        $tokenResolver = \Mockery::mock(TokenResolverInterface::class);
        $baseUrl = 'https://www.greatfoods.test/api/v1';

        $menuService = new MenuService($menuClient, $tokenResolver, $baseUrl);
        $productService = new ProductService($productClient, $tokenResolver, $baseUrl);

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
        $menuResponse = \Mockery::mock(ResponseInterface::class);
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
        $productResponse = \Mockery::mock(ResponseInterface::class);
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

        // When
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
     * Runs the scenario two test
     *
     * @dataProvider scenarioTwoProvider
     * @param array $product The product
     *
     * @return void
     */
    public function testScenarioTwo(array $product): void
    {
        $product = new Product($product);

        $client = \Mockery::mock(ClientInterface::class);
        $tokenResolver = \Mockery::mock(TokenResolverInterface::class);
        $baseUrl = 'https://www.greatfoods.test/api/v1';

        $service = new ProductService($client, $tokenResolver, $baseUrl);

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
        $response = \Mockery::mock(ResponseInterface::class);
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

    /**
     * Provides data for scenario one
     *
     * @return array
     */
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

    /**
     * Provides data for scenario two
     *
     * @return array
     */
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