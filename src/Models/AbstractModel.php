<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Models;

use GreatFoods\APIHandler\Contracts\Models\AbstractModel as AbstractModelInterface;
use GreatFoods\APIHandler\Exceptions\NotFoundException;
use OutOfBoundsException;

/**
 * AbstractModel Class
 *
 * @package GreatFoods\APIHandler\Models
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class AbstractModel implements AbstractModelInterface
{
    /**
     * The model attributes
     *
     * @var array
     */
    protected array $attributes;

    /**
     * The model meta data
     *
     * @var array
     */
    protected array $meta;

    /**
     * Constructor
     *
     * @param array $data The data to map to the model
     */
    public function __construct(array $data)
    {
        $this->setData($data);
    }

    /**
     * Get Magic Method
     *
     * @param string $key The key of the attribute to get
     *
     * @return void
     */
    public function __get(string $key): void
    {
        throw new OutOfBoundsException('Get magic method is disabled.');
    }

    /**
     * Isset Magic Method
     *
     * @param string $key The key of the attribute to check
     *
     * @return bool True if found, else false
     */
    public function __isset(string $key): bool
    {
        throw new OutOfBoundsException('Isset magic method is disabled.');
    }

    /**
     * Set Magic Method
     *
     * @param string $key The key of the attribute to set
     * @param mixed $value The value to set to the attribute
     *
     * @return void
     */
    public function __set(string $key, mixed $value): void
    {
        throw new OutOfBoundsException('Set magic method is disabled.');
    }

    /**
     * Unset Magic Method
     *
     * @param string $key The key of the attribute to unset
     *
     * @return void
     */
    public function __unset(string $key): void
    {
        throw new OutOfBoundsException('Unset magic method is disabled.');
    }

    /**
     * Gets all the meta data from the model
     *
     * @return array The meta data
     */
    public function getAllMetaData(): array
    {
        return $this->meta;
    }

    /**
     * Get a single attribute by its key
     *
     * @param string $key The key of the attribute to get
     *
     * @return mixed The value of the attribute
     */
    public function getAttribute(string $key): mixed
    {
        if (! $this->keyExists($key, false)) {
            throw new NotFoundException(sprintf('No attribute with the key "%s" found.', $key));
        }

        return $this->attributes[$key];
    }

    /**
     * Gets all the attributes from the model
     *
     * @return array An array of attributes and their values
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Gets a single piece of meta data by its key
     *
     * @param string $key The key of the meta data
     * @return mixed The value of the meta data
     */
    public function getMetaData(string $key): mixed
    {
        if (! $this->keyExists($key, true)) {
            throw new NotFoundException(sprintf('No meta data with the key "%s" found.', $key));
        }

        return $this->meta[$key];
    }

    /**
     * Checks if an attribute or meta data key exists
     *
     * @param string $key The key to check
     * @param bool $meta If true then check meta data not attributes
     *
     * @return bool True if exists, else false
     */
    protected function keyExists(string $key, bool $meta = false): bool
    {
        if ($meta === false) {
            return array_key_exists($key, $this->attributes);
        }

        return array_key_exists($key, $this->meta);
    }

    /**
     * Save a key value to an attribute
     *
     * @param string $key The key to save
     * @param mixed $value The value to save
     *
     * @return void
     */
    protected function setAttribute(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Sets the attributes and meta data
     *
     * @param array $data The data to assign
     *
     * @return void
     */
    protected function setData(array $data): void
    {
        foreach ($data as $key => $value) {
            if ($this->keyExists($key, false)) {
                $this->setAttribute($key, $value);
                continue;
            }

            $this->setMetaData($key, $value);
        }
    }

    /**
     * Save a key value to meta data
     *
     * @param string $key The key to save
     * @param mixed $value The value to save
     *
     * @return void
     */
    protected function setMetaData(string $key, mixed $value): void
    {
        $this->meta[$key] = $value;
    }
}