<?php 

namespace App\Tests\Form;

use App\Form\LoginType;
use App\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;

class LoginTest extends TypeTestCase
{
     /** @test */
    public function submit_valid_data()
    {
        $formData = array(
            'email' => 'lasekmiroslaw@gmail.com',
            'password' => 'pass',
        );

        $form = $this->factory->create(LoginType::class);

        $object = new User();
        $object->setEmail('lasekmiroslaw@gmail.com');
        $object->setPassword('pass');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
