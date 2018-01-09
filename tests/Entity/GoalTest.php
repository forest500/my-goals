<?php 

namespace App\Tests\Entity;

use \App\Entity\Goal;
use Symfony\Component\Validator\Validation;
use Doctrine\Common\Collections\ArrayCollection;

class GoalTest extends \PHPUnit\Framework\TestCase

{
    protected $goal;
    protected $validator;

    public function setUp()
    {
        $this->goal = new Goal();
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }   

    /** @test */
    public function constructor_set_status_to_true()
    {
        $this->assertTrue($this->goal->getStatus());
    }        

    /** @test */
    public function that_we_can_get_valid_name()
    {
        $this->goal->setName('career');

        $errors = $this->validator->validate($this->goal);

        $this->assertEquals('career', $this->goal->getName());
        $this->assertEquals(0, count($errors));
    }     

    /** @test */
    public function that_we_can_get_valid_status()
    {
        $this->goal->setName('career');
        $this->goal->setStatus(true);

        $errors = $this->validator->validate($this->goal);

        $this->assertEquals(true, $this->goal->getStatus());
        $this->assertEquals(0, count($errors));
    }     

     /** @test */
    public function that_we_can_get_category()
    {
        $category = new \App\Entity\Category;
        $this->goal->setCategory($category);

        $this->assertEquals($category, $this->goal->getCategory());
    }     
}