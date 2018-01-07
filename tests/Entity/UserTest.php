<?php 

namespace App\Tests\Entity;

use \App\Entity\User;
use Symfony\Component\Validator\Validation;

//Test works only if you delete @UniqueEntity("email", message="Użytkownik o podanym email już istnieję")
class UserTest extends \PHPUnit\Framework\TestCase

{
    protected $user;
    protected $validator;

    public function setUp()
    {
        $this->user = new User();
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    /** @test */
    public function constructor_set_is_active_to_false()
    {
        $this->assertFalse($this->user->getIsActive());
    }

    /** @test */
    public function that_we_can_get_valid_email()
    {
        $this->user->setEmail('lasekmiroslaw@gmail.com');
        $this->user->setPlainPassword('password');
        $errors = $this->validator->validate($this->user);

        $this->assertEquals('lasekmiroslaw@gmail.com', $this->user->getEmail());
        $this->assertEquals(0, count($errors));
    } 

    /** @test */
    public function that_we_can_get_valid_plain_password()
    {
        $this->user->setPlainPassword('password');
        $this->user->setEmail('lasekmiroslaw@gmail.com');
        $errors = $this->validator->validate($this->user);

        $this->assertEquals('password', $this->user->getPlainPassword());
        $this->assertEquals(0, count($errors));
    }

    /** @test */
    public function that_we_can_get_password()
    {
        $this->user->setPassword('password');

        $this->assertEquals('password', $this->user->getPassword());
    }    

    /** @test */
    public function that_we_can_get_valid_is_active()
    {
        $this->user->setPlainPassword('password');
        $this->user->setEmail('lasekmiroslaw@gmail.com');
        $this->user->setIsActive(true);

        $errors = $this->validator->validate($this->user);

        $this->assertEquals(true, $this->user->getIsActive());
        $this->assertEquals(0, count($errors));
    }       

    /** @test */
    public function get_salt_return_null()
    {
        $this->assertNull($this->user->getSalt());
    }

    /** @test */
    public function get_roles_return_array()
    {
        $this->assertInternalType('array', $this->user->getRoles());
    }    

    /** @test */
    public function get_roles_return_array_with_value_ROLE_USER()
    {
        $this->assertEquals('ROLE_USER', $this->user->getRoles()[0]);
    }       
    
    /** @test */
    public function is_account_non_expired_return_true()
    {
        $this->assertTrue($this->user->isAccountNonExpired());
    }  

    /** @test */
    public function is_account_non_locked_return_true()
    {
        $this->assertTrue($this->user->isAccountNonLocked());
    }     

    /** @test */
    public function that_is_credentials_non_expired_return_true()
    {
        $this->assertTrue($this->user->isCredentialsNonExpired());
    }
    
    /** @test */
    public function is_enabled_return_user_is_active()
    {
        $this->user->setIsActive(true);
        $this->assertEquals(true, $this->user->isEnabled());
    }
    
    /** @test */
    public function get_username_return_user_email()
    {
        $this->user->SetEmail('lasekmiroslaw@gmail.com');
        $this->assertEquals('lasekmiroslaw@gmail.com', $this->user->getUsername());
    } 

    /** @test */
    public function serialize_return_serialized_array()
    {
        $this->user->setPassword('password');
        $this->user->setEmail('lasekmiroslaw@gmail.com');
        $this->user->setIsActive(true);
        $serialized = serialize(array(
            $this->user->getId(),
            $this->user->getEmail(),
            $this->user->getPassword(),
            $this->user->getIsActive(),
        ));

        $this->assertEquals($serialized, $this->user->serialize());
    }  
}