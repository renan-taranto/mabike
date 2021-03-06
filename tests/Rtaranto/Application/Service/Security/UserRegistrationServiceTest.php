<?php
namespace Rtaranto\Tests\Application\Service;

use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Security\UserRegistrationService;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Factory\UserFactoryInterface;
use Rtaranto\Domain\Entity\Repository\UserRepositoryInterface;
use Rtaranto\Domain\Entity\User;

class UserRegistrationServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterInvalidUserThrowsException()
    {
        $userFactory = $this->getMock(UserFactoryInterface::class);
        
        $userRepository = $this->getMock(UserRepositoryInterface::class);
                
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('throwValidationFailedIfNotValid')
            ->will($this->throwException(new ValidationFailedException(array())));
        
        $registerUserService = new UserRegistrationService($userFactory, $userRepository, $validator);
        
        $this->setExpectedException(ValidationFailedException::class);
        
        $registerUserService->registerUser('user', 'email', 'pass', array());
    }
    
    /**
     * @dataProvider provider
     */
    public function testRegisterReturnsUser($user)
    {
        $userFactory = $this->getMock(UserFactoryInterface::class);
        $userFactory->expects($this->once())
            ->method('createUser')
            ->will($this->returnValue($user));
        
        $userRepository = $this->getMock(UserRepositoryInterface::class);
        $userRepository->expects($this->once())
            ->method('addUser')
            ->will($this->returnValue($user));
        
        $validator = $this->getMock(ValidatorInterface::class);
                
        $registerUserService = new UserRegistrationService($userFactory, $userRepository, $validator);
        $registeredUser = $registerUserService->registerUser('user', 'email', 'pass', array(User::ROLE_USER));
        
        $this->assertEquals($user, $registeredUser);
    }
    
    public function provider()
    {
        $username = 'normal_user';
        $email = 'user@email.com';
        $password = 'plainPass';
        $roles = array(User::ROLE_USER);
        $user = new User($username, $email, $password, $roles);
        
        return array(array($user));
    }
}
