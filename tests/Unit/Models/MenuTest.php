<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Tests\Unit\Models;

use GreatFoods\APIHandler\Contracts\Models\Menu as MenuInterface;
use GreatFoods\APIHandler\Models\Menu;
use GreatFoods\APIHandler\Models\Product;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase
{
    public function testInstance(): void
    {
        // Arrange
        $data = [
            'id' => 1,
            'name' => 'Starters'
        ];

        // Act
        $model = new Menu($data);

        // Assert
        $this->assertInstanceOf(MenuInterface::class, $model);
    }

    public function testGetId(): void
    {
        // Arrange
        $data = [
            'id' => 1,
            'name' => 'Starters'
        ];

        $model = new Menu($data);

        // Act
        $id = $model->getId();

        // Assert
        $this->assertIsString($id);
        $this->assertEquals($id, (string) $data['id']);
    }

    public function testGetName(): void
    {
        // Arrange
        $data = [
            'id' => 1,
            'name' => 'Starters'
        ];

        $model = new Menu($data);

        // Act
        $name = $model->getName();

        // Assert
        $this->assertIsString($name);
        $this->assertEquals($name, $data['name']);
    }

    public function testGetProducts(): void
    {
        // Arrange
        $data = [
            'id' => 1,
            'name' => 'Starters',
            'products' => [
                new Product(['id' => 1, 'name' => 'Garlic Bread']),
                new Product(['id' => 2, 'name' => 'Vegetable Samosa']),
            ]
        ];

        $model = new Menu($data);

        // Act
        $products = $model->getProducts();

        // Assert
        $this->assertIsArray($products);
        $this->assertEquals($data['products'][0]->getId(), $products[0]->getId());
        $this->assertEquals($data['products'][0]->getName(), $products[0]->getName());
    }
}