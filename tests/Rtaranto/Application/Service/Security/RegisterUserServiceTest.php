<?php
namespace Rtaranto\Tests\Application\Service;

use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Security\UserRegistrationService;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Factory\UserFactory;
use Rtaranto\Domain\Entity\Repository\UserRepositoryInterface;
use Rtaranto\Domain\Entity\User;

class RegisterUserServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterInvalidUserThrowsException()
    {
        $userFactory = $this->getMock(UserFactory::class);
        
        $userRepository = $this->getMock(UserRepositoryInterface::class);
                
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));
        $validator->expects($this->once())
            ->method('getErrors')
            ->will($this->returnValue(array()));
        $registerUserService = new UserRegistrationService($userFactory, $userRepository, $validator);
        
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
        
        $userRepository = $this->getMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())
            ->method('addUser')
            ->will($this->returnValue($user));
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        
        $registerUserService = new UserRegistrationService($userFactory, $userRepository, $validator);
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
