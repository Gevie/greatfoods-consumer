<?php

declare(strict_types=1);

namespace GreatFoods\APIHandler\Tests\Unit\Models;

use GreatFoods\APIHandler\Contracts\Models\Menu as MenuInterface;
use GreatFoods\APIHandler\Models\Menu;
use PHPUnit\Framework\TestCase;

/**
 * MenuTest Class
 *
 * @package GreatFoods\APIHandler\Tests\Unit\Models
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class MenuTest extends TestCase
{
    /**
     * Test the instance of Menu
     *
     * @return void
     */
    public function testInstance(): void
    {
        // Give
        $data = [
            'id' => 1,
            'name' => 'Starters'
        ];

        $model = new Menu($data);

        // Then
        $this->assertInstanceOf(MenuInterface::class, $model);
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
            'name' => 'Starters'
        ];

        $model = new Menu($data);

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
            'name' => 'Starters'
        ];

        $model = new Menu($data);

        // When
        $name = $model->getName();

        // Then
        $this->assertIsString($name);
        $this->assertEquals($name, $data['name']);
    }
}