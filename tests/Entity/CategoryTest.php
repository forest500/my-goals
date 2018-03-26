<?php

namespace App\Tests\Entity;

use \App\Entity\Category;
use Symfony\Component\Validator\Validation;
use Doctrine\Common\Collections\ArrayCollection;

class CategoryTest extends \PHPUnit\Framework\TestCase

{
    protected $category;
    protected $validator;

    public function setUp()
    {
        $this->category = new Category();
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    /** @test */
    public function that_we_can_get_valid_name()
    {
        $this->category->setName('career');

        $errors = $this->validator->validate($this->category);

        $this->assertEquals('career', $this->category->getName());
        $this->assertEquals(0, count($errors));
    }

    /** @test */
    public function that_we_can_get_valid_valid_description()
    {
        $this->category->setDescription('Here are my career goals');
        $this->category->setName('career');
        $errors = $this->validator->validate($this->category);

        $this->assertEquals('Here are my career goals', $this->category->getDescription());
        $this->assertEquals(0, count($errors));
    }


}
