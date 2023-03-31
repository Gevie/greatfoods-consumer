<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Models;

use GreatFoods\APIHandler\Contracts\Models\Menu as MenuInterface;

/**
 * Menu Class
 *
 * @package GreatFoods\APIHandler\Models
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class Menu extends Model implements MenuInterface
{
    /**
     * The model attributes
     *
     * @var array
     */
    protected array $attributes = [
        'id' => null,
        'name' => null
    ];

    /**
     * The model meta data
     *
     * @var array
     */
    protected array $meta = [];

    /**
     * Get the id of the menu
     *
     * @return string The menu id
     */
    public function getId(): string
    {
        return (string) $this->getAttribute('id');
    }

    /**
     * Get the name of the menu
     *
     * @return string The menu name
     */
    public function getName(): string
    {
        return $this->getAttribute('name');
    }
}