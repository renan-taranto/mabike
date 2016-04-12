<?php
namespace Tests\Rtaranto\Domain\Entity\Factory;

use Rtaranto\Domain\Entity\Factory\UserFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryReturnsUserWithEncodedPassword()
    {
        $passwordEncoder = $this->getMock(UserPasswordEncoderInterface::class);
        $passwordEncoder->expects($this->once())
            ->method('encodePassword')
            ->will($this->returnValue('encodedPassword'));
        
        $factory = new UserFactory($passwordEncoder);
        
        $username = 'normal_user';
        $email = 'user@email.com';
        $password = 'plainPass';
        $roles = array('ROLE_USER');
        $user = $factory->createUser($username, $email, $password, $roles);
        
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($roles, $user->getRoles());
        $this->assertNotEquals($password, $user->getPassword());
        $this->assertNotNull($user->getPassword());
    }
}