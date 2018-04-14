<?php

namespace App\Tests\Entity;

use \App\Entity\Category;

class CategoryTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function that_we_can_get_namey()
    {
        $category = new Category;
        $category->setName('name');

        $this->assertEquals('name', $category->getName());
    }
}
