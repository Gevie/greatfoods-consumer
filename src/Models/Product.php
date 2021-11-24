<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Models;

use GreatFoods\APIHandler\Contracts\Models\Product as ProductInterface;

/**
 * Product Class
 *
 * @package GreatFoods\APIHandler\Models
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class Product extends AbstractModel implements ProductInterface
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
     * Get the id of the product
     *
     * @return string The product id
     */
    public function getId(): string
    {
        return (string) $this->getAttribute('id');
    }

    /**
     * Get the name of the product
     *
     * @return string The product name
     */
    public function getName(): string
    {
        return $this->getAttribute('name');
    }
}