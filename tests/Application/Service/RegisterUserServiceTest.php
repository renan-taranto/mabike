<?php
namespace Tests\Application\Service;

use Application\Exception\ValidationFailedException;
use Application\Service\Security\RegisterUserService;
use Application\Service\Validator\ValidatorInterface;
use Domain\Entity\Factory\UserFactory;
use Domain\Entity\Repository\UserRepository;
use Domain\Entity\User;

class RegisterUserServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterInvalidUserThrowsException()
    {
        $userFactory = $this->getMock(UserFactory::class);
        
        $userRepository = $this->getMock(UserRepository::class);
                
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));
        $validator->expects($this->once())
            ->method('getErrors')
            ->will($this->returnValue(array()));
        $registerUserService = new RegisterUserService($userFactory, $userRepository, $validator);
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $registerUserService->registerUser('user', 'email', 'pass');
    }
    
    /**
     * @dataProvider provider
     */
    public function testRegisterReturnsUser($user)
    {
        $userFactory = $this->getMock(UserFactory::class);
        $userFactory->expects($this->once())
            ->method('createUser')
            ->will($this->returnValue($user));
        
        $userRepository = $this->getMock(UserRepository::class);
        $userRepository->expects($this->once())
            ->method('addUser')
            ->will($this->returnValue($user));
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        
        $registerUserService = new RegisterUserService($userFactory, $userRepository, $validator);
        $registeredUser = $registerUserService->registerUser('user', 'email', 'pass');
        
        $this->assertEquals($user, $registeredUser);
    }
    
    public function provider()
    {
        $username = 'normal_user';
        $email = 'user@email.com';
        $password = 'plainPass';
        $user = new User($username, $email, $password);
        
        return array(array($user));
    }
}
