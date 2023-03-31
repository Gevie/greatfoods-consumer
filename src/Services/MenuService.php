<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Services;

use GreatFoods\APIHandler\Contracts\Services\MenuService as MenuServiceInterface;
use GreatFoods\APIHandler\Contracts\Services\Resolvers\TokenResolver as TokenResolverInterface;
use GreatFoods\APIHandler\Exceptions\NotFoundException;
use GreatFoods\APIHandler\Mappers\MenuMapper;
use GuzzleHttp\ClientInterface;

class MenuService extends ApiService implements MenuServiceInterface
{
    public function __construct(
        protected ClientInterface $client,
        protected TokenResolverInterface $tokenResolver,
        protected string $url,
        protected MenuMapper $menuMapper
    ) {
        // ...
    }

    public function get(): array
    {
        $response = $this->call('GET', 'menus');

        if (empty($response['data'])) {
            throw new NotFoundException('Could not find any menus.');
        }

        return array_map(fn($menuData) => $this->menuMapper->map($menuData), $response['data']);
    }
}