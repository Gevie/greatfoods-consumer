<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Services;

use GreatFoods\APIHandler\Contracts\Services\MenuService as MenuServiceInterface;
use GreatFoods\APIHandler\Exceptions\NotFoundException;
use GreatFoods\APIHandler\Models\Menu;

/**
 * MenuService Class
 *
 * @package GreatFoods\APIHandler\Services
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class MenuService extends AbstractService implements MenuServiceInterface
{
    /**
     * Get all the menus for Great Foods
     *
     * @return array An array of menu models
     */
    public function get(): array
    {
        $response = $this->call('GET', 'menus');

        if (empty($response['data'])) {
            throw new NotFoundException('Could not find any menus.');
        }

        $menus = [];
        foreach ($response['data'] as $menu) {
            $menus[] = new Menu($menu);
        }

        return $menus;
    }
}