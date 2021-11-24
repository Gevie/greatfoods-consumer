<?php

namespace GreatFoods\APIHandler\Contracts\Services;

/**
 * MenuService Interface
 *
 * @package GreatFoods\APIHandler\Contracts\Services
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface MenuService
{
    /**
     * Get all the menus for Great Foods
     *
     * @return array An array of menu models
     */
    public function get(): array;
}