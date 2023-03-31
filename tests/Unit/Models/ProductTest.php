<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Tests\Unit\Models;

use GreatFoods\APIHandler\Contracts\Models\Product as ProductInterface;
use GreatFoods\APIHandler\Models\Product;
use PHPUnit\Framework\TestCase;

/**
 * ProductTest Class
 *
 * @package GreatFoods\APIHandler\Tests\Unit\Models
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class ProductTest extends TestCase
{
    public function testInstance(): void
    {
        // Arrange
        $data = [
            'id' => 1,
            'name' => 'Large Pizza'
        ];

        // Act
        $model = new Product($data);

        // Assert
        $this->assertInstanceOf(ProductInterface::class, $model);
    }

    public function testGetId(): void
    {
        // Arrange
        $data = [
            'id' => 1,
            'name' => 'Large Pizza'
        ];

        $model = new Product($data);

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
            'name' => 'Large Pizza'
        ];

        $model = new Product($data);

        // Act
        $name = $model->getName();

        // Assert
        $this->assertIsString($name);
        $this->assertEquals($name, $data['name']);
    }
}