<?php
namespace Tests\Application\Service;

use Application\Command\RegisterUserCommand;
use Application\Command\RegisterUserDTO;
use Application\Service\RegisterUserService;
use Domain\Entity\User;

class RegisterUserCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testCommandReturnsUser()
    {
        $username = 'normal_user';
        $email = 'user@email.com';
        $password = 'plainPass';
        
        $user = new User($username, $email, $password);
        
        $registerUserService = $this->getMockBuilder(RegisterUserService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $registerUserService->expects($this->once())
            ->method('registerUser')
            ->will($this->returnValue($user));
        
        $registerUserDTO = new RegisterUserDTO();
        $registerUserDTO->setUsername($username);
        $registerUserDTO->setEmail($email);
        $registerUserDTO->setPassword($password);

        $registerUserCommand = new RegisterUserCommand($registerUserService);
        $registeredUser = $registerUserCommand->execute($registerUserDTO);
        
        $this->assertEquals($user, $registeredUser);
    }
}
