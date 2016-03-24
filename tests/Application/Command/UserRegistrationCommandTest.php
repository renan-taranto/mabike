<?php
namespace Tests\Application\Command;

use Application\Command\UserRegistrationCommand;
use Application\Dto\UserRegistration;
use Application\Service\RegisterUserService;
use Domain\Entity\User;

class UserRegistrationCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testCommandReturnsUser()
    {
        $registerUserDTO = new UserRegistration();
        $registerUserDTO->setUsername('normal_user');
        $registerUserDTO->setEmail('user@email.com');
        $registerUserDTO->setPassword('plainPass');
        
        $user = new User(
            $registerUserDTO->getUsername(),
            $registerUserDTO->getEmail(),
            $registerUserDTO->getPassword());
        
        $registerUserService = $this->getMockBuilder(RegisterUserService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $registerUserService->expects($this->once())
            ->method('registerUser')
            ->will($this->returnValue($user));
        

        $registerUserCommand = new UserRegistrationCommand($registerUserService);
        $registeredUser = $registerUserCommand->execute($registerUserDTO);
        
        $this->assertEquals($user, $registeredUser);
    }
}
