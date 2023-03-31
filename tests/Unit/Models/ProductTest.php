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
    /**
     * Test the instance of Product
     *
     * @return void
     */
    public function testInstance(): void
    {
        // Give
        $data = [
            'id' => 1,
            'name' => 'Large Pizza'
        ];

        $model = new Product($data);

        // Then
        $this->assertInstanceOf(ProductInterface::class, $model);
    }

    /**
     * Test the getId method
     *
     * @return void
     */
    public function testGetId(): void
    {
        // Give
        $data = [
            'id' => 1,
            'name' => 'Large Pizza'
        ];

        $model = new Product($data);

        // When
        $id = $model->getId();

        // Then
        $this->assertIsString($id);
        $this->assertEquals($id, (string) $data['id']);
    }

    /**
     * Test the getName() method
     *
     * @return void
     */
    public function testGetName(): void
    {
        // Give
        $data = [
            'id' => 1,
            'name' => 'Large Pizza'
        ];

        $model = new Product($data);

        // When
        $name = $model->getName();

        // Then
        $this->assertIsString($name);
        $this->assertEquals($name, $data['name']);
    }
}