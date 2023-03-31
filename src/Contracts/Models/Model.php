<?php

namespace GreatFoods\APIHandler\Contracts\Models;

/**
 * Model Interface
 *
 * @package GreatFoods\APIHandler\Contracts\Models
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface Model
{
    /**
     * Get Magic Method
     *
     * @param string $key The key of the attribute to get
     *
     * @return void
     */
    public function __get(string $key): void;

    /**
     * Isset Magic Method
     *
     * @param string $key The key of the attribute to check
     *
     * @return bool True if found, else false
     */
    public function __isset(string $key): bool;

    /**
     * Set Magic Method
     *
     * @param string $key The key of the attribute to set
     * @param mixed $value The value to set to the attribute
     *
     * @return void
     */
    public function __set(string $key, mixed $value): void;

    /**
     * Unset Magic Method
     *
     * @param string $key The key of the attribute to unset
     *
     * @return void
     */
    public function __unset(string $key): void;

    /**
     * Gets all the meta data from the model
     *
     * @return array The meta data
     */
    public function getAllMetaData(): array;

    /**
     * Get a single attribute by its key
     *
     * @param string $key The key of the attribute to get
     *
     * @return mixed The value of the attribute
     */
    public function getAttribute(string $key): mixed;

    /**
     * Gets all the attributes from the model
     *
     * @return array An array of attributes and their values
     */
    public function getAttributes(): array;

    /**
     * Gets a single piece of meta data by its key
     *
     * @param string $key The key of the meta data
     * @return mixed The value of the meta data
     */
    public function getMetaData(string $key): mixed;
}