<?php

namespace GreatFoods\APIHandler\Contracts\Models;

/**
 * Menu Interface
 *
 * @package GreatFoods\APIHandler\Contracts\Models
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface Menu
{
    /**
     * Get the id of the menu
     *
     * @return string The menu id
     */
    public function getId(): string;

    /**
     * Get the name of the menu
     *
     * @return string The menu name
     */
    public function getName(): string;
}