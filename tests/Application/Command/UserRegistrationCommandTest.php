<?php
namespace Tests\Application\Command;

use Application\Command\Security\UserRegistrationCommand;
use Application\Dto\Security\UserRegistrationDTO;
use Application\Service\Security\UserRegistration;
use Domain\Entity\User;

class UserRegistrationCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testCommandReturnsUser()
    {
        $registerUserDTO = new UserRegistrationDTO();
        $registerUserDTO->setUsername('normal_user');
        $registerUserDTO->setEmail('user@email.com');
        $registerUserDTO->setPassword('plainPass');
        
        $user = new User(
            $registerUserDTO->getUsername(),
            $registerUserDTO->getEmail(),
            $registerUserDTO->getPassword());
        
        $userRegistration = $this->getMock(UserRegistration::class);
        $userRegistration->expects($this->once())
            ->method('registerUser')
            ->will($this->returnValue($user));
        

        $registerUserCommand = new UserRegistrationCommand($userRegistration);
        $registeredUser = $registerUserCommand->execute($registerUserDTO);
        
        $this->assertEquals($user, $registeredUser);
    }
}
