<?php

namespace App\Tests\Entity;

use \App\Entity\Stage;
use Symfony\Component\Validator\Validation;
use Doctrine\Common\Collections\ArrayCollection;

class StageTest extends \PHPUnit\Framework\TestCase

{
    protected $stage;
    protected $validator;

    public function setUp()
    {
        $this->stage = new Stage();
        $this->stage->setName('junior developer');
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
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
    public function that_we_can_get_valid_number()
    {
        $this->stage->setNumber(15);
        $errors = $this->validator->validate($this->stage);

        $this->assertEquals(15, $this->stage->getNumber());
        $this->assertEquals(0, count($errors));
    }

    /** @test */
    public function that_we_can_get_valid_name()
    {
        $errors = $this->validator->validate($this->stage);

        $this->assertEquals('junior developer', $this->stage->getName());
        $this->assertEquals(0, count($errors));
    }

    /** @test */
    public function that_we_can_get_valid_status()
    {
        $this->stage->setStatus(true);
        $errors = $this->validator->validate($this->stage);

        $this->assertEquals(true, $this->stage->getStatus());
        $this->assertEquals(0, count($errors));
    }

    /** @test */
    public function that_we_can_get_valid_award()
    {
        $this->stage->setAward('one million dollars');
        $errors = $this->validator->validate($this->stage);

        $this->assertEquals('one million dollars', $this->stage->getAward());
        $this->assertEquals(0, count($errors));
    }

    /** @test */
    public function that_we_can_get_message_when_award_is_too_short()
    {
        $this->stage->setAward('on');
        $errors = $this->validator->validate($this->stage);

        $this->assertEquals('Nazwa nagrody musi zawierać conajmniej 3 znaki', $errors[0]->getMessage());
    }

    /** @test */
    public function that_we_can_get_valid_end_date()
    {
        $this->stage->setEndDate('2018-01-01');
        $errors = $this->validator->validate($this->stage);

        $this->assertEquals('2018-01-01', $this->stage->getEndDate());
        $this->assertEquals(0, count($errors));
    }

    /** @test */
    public function that_we_can_get_message_when_end_date_is_not_valid_format()
    {
        $this->stage->setEndDate('10-01-2017');
        $errors = $this->validator->validate($this->stage);

        $this->assertEquals('Data musi mieć format YYYY-mm-dd', $errors[0]->getMessage());
    }

     /** @test */
    public function that_we_can_get_goal()
    {
        $goal = new \App\Entity\Goal;
        $this->stage->setGoal($goal);

        $this->assertEquals($goal, $this->stage->getGoal());
    }
}
