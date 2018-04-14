<?php

namespace App\Tests\Entity;

use \App\Entity\Stage;
use Doctrine\Common\Collections\ArrayCollection;

class StageTest extends \PHPUnit\Framework\TestCase

{
    protected $stage;
    protected $validator;

    public function setUp()
    {
        $this->stage = new Stage();
        $this->stage->setName('junior developer');
    }

    /** @test */
    public function constructor_set_status_to_true()
    {
        $this->assertTrue($this->stage->getStatus());
    }

     /** @test */
    public function auto_set_number_have_set_number()
    {
        $stub = $this->createMock(\App\Entity\Goal::class);
        $stages = new ArrayCollection([$this->stage, new Stage()]);
        $stub->method('getStages')
            ->willReturn($stages);

        $this->stage->setGoal($stub);
        $this->stage->autoSetNumber();

        $this->assertEquals(3, $this->stage->getNumber());
    }

     /** @test */
    public function that_we_can_get_goal()
    {
        $goal = new \App\Entity\Goal;
        $this->stage->setGoal($goal);

        $this->assertEquals($goal, $this->stage->getGoal());
    }
}
