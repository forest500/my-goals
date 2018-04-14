<?php

namespace App\Tests\Entity;

use \App\Entity\Goal;
use \App\Entity\Category;

class GoalTest extends \PHPUnit\Framework\TestCase

{

    public function setUp()
    {
        $this->goal = new Goal();
    }

    /** @test */
    public function constructor_set_status_to_true()
    {
        $this->assertTrue($this->goal->getStatus());
    }

     /** @test */
    public function that_we_can_get_category()
    {
        $category = new Category;
        $this->goal->setCategory($category);

        $this->assertEquals($category, $this->goal->getCategory());
    }
}
