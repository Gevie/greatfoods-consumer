<?php

namespace GreatFoods\APIHandler\Contracts\Models;

/**
 * Product Interface
 *
 * @package GreatFoods\APIHandler\Contracts\Models
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface Product
{
    /**
     * Get the id of the product
     *
     * @return string The product id
     */
    public function getId(): string;

    /**
     * Get the name of the product
     *
     * @return string The product name
     */
    public function getName(): string;
}