<?php
namespace Tests\Application\Service;

use Application\Service\RegisterUserService;
use Domain\Entity\Factory\UserFactory;
use Domain\Entity\Repository\UserRepository;
use Domain\Entity\User;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterUserServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterInvalidUserThrowsException()
    {
        $userFactory = $this->getMock(UserFactory::class);
        
        $userRepository = $this->getMock(UserRepository::class);
        
        $constraintViolation = $this->getMock(ConstraintViolationInterface::class);
        $constraintViolation->expects($this->once())
            ->method('getMessage')
            ->will($this->returnValue('error1'));
        
        $validator = $this->getMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(array($constraintViolation)));
        $registerUserService = new RegisterUserService($userFactory, $userRepository, $validator);
        
        $this->setExpectedException('Exception');
        
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
