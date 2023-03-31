<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Models;

use GreatFoods\APIHandler\Contracts\Models\Model as ModelInterface;
use GreatFoods\APIHandler\Exceptions\NotFoundException;
use OutOfBoundsException;

 abstract readonly class Model implements ModelInterface
{
    protected array $attributes;

    protected array $meta;

    public function __construct(array $data)
    {
        $this->setData($data);
    }

    public function __get(string $key): void
    {
        throw new OutOfBoundsException('Get magic method is disabled.');
    }

    public function __isset(string $key): bool
    {
        throw new OutOfBoundsException('Isset magic method is disabled.');
    }

    public function __set(string $key, mixed $value): void
    {
        throw new OutOfBoundsException('Set magic method is disabled.');
    }

    public function __unset(string $key): void
    {
        throw new OutOfBoundsException('Unset magic method is disabled.');
    }

    public function getAllMetaData(): array
    {
        return $this->meta;
    }

    public function getAttribute(string $key): mixed
    {
        if (! $this->keyExists($key, false)) {
            throw new NotFoundException(sprintf('No attribute with the key "%s" found.', $key));
        }

        return $this->attributes[$key];
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getMetaData(string $key): mixed
    {
        if (! $this->keyExists($key, true)) {
            throw new NotFoundException(sprintf('No meta data with the key "%s" found.', $key));
        }

        return $this->meta[$key];
    }

    protected function keyExists(string $key, bool $meta = false): bool
    {
        if ($meta === false) {
            return array_key_exists($key, $this->attributes);
        }

        return array_key_exists($key, $this->meta);
    }

    protected function setAttribute(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

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

    protected function setMetaData(string $key, mixed $value): void
    {
        $this->meta[$key] = $value;
    }
}