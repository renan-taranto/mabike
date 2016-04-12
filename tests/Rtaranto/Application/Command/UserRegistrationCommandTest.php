<?php
namespace Tests\Rtaranto\Application\Command;

use Rtaranto\Application\Command\Security\UserRegistrationCommand;
use Rtaranto\Application\Dto\Security\UserRegistrationDTO;
use Rtaranto\Application\Service\Security\UserRegistrationInterface;
use Rtaranto\Domain\Entity\User;

class UserRegistrationCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testCommandReturnsUser()
    {
        $registerUserDTO = new UserRegistrationDTO(array(User::ROLE_USER));
        $registerUserDTO->setUsername('normal_user');
        $registerUserDTO->setEmail('user@email.com');
        $registerUserDTO->setPassword('plainPass');
        
        $user = new User(
            $registerUserDTO->getUsername(),
            $registerUserDTO->getEmail(),
            $registerUserDTO->getPassword(),
            $registerUserDTO->getRoles()
        );
        
        $userRegistration = $this->getMock(UserRegistrationInterface::class);
        $userRegistration->expects($this->once())
            ->method('registerUser')
            ->will($this->returnValue($user));
        

        $registerUserCommand = new UserRegistrationCommand($userRegistration);
        $registeredUser = $registerUserCommand->execute($registerUserDTO);
        
        $this->assertEquals($user, $registeredUser);
    }
}
