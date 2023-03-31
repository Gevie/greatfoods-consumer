<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Tests\Unit\Models;

use GreatFoods\APIHandler\Contracts\Models\Menu as MenuInterface;
use GreatFoods\APIHandler\Models\Menu;
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
}