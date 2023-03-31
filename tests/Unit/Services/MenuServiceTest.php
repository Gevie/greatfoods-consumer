<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Tests\Unit\Services;

use GreatFoods\APIHandler\Contracts\Models\Menu as MenuInterface;
use GreatFoods\APIHandler\Contracts\Services\Resolvers\TokenResolver as TokenResolverInterface;
use GreatFoods\APIHandler\Services\MenuService;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class MenuServiceTest extends TestCase
{
    /**
     * @dataProvider menusProvider
     */
    public function testGetMenus($menus): void
    {
        // Arrange
        $client = \Mockery::mock(ClientInterface::class);
        $tokenResolver = \Mockery::mock(TokenResolverInterface::class);
        $baseUrl = 'https://www.greatfoods.test/api/v1';

        $service = new MenuService($client, $tokenResolver, $baseUrl);

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
        $response = \Mockery::mock(ResponseInterface::class);
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

        // Act
        $result = $service->get();

        // Assert
        $this->assertIsArray($result);
        foreach ($result as $key => $menu) {
            $this->assertInstanceOf(MenuInterface::class, $menu);
            $this->assertEquals($menu->getId(), (string) $menus['data'][$key]['id']);
            $this->assertEquals($menu->getName(), (string) $menus['data'][$key]['name']);
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
                ]
            ]
        ];
    }
}