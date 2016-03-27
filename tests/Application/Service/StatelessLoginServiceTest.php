<?php
namespace Tests\Application\Service;

use Application\Dto\Security\AuthenticationTokenDTO;
use Application\Service\Security\PasswordValidator;
use Application\Service\Security\UserTokenGenerator;
use Application\Service\Security\StatelessLoginService;
use DateTime;
use Domain\Entity\Repository\UserRepository;
use Domain\Entity\User;
use Exception;

class StatelessLoginServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullLogin()
    {
        $authToken = new AuthenticationTokenDTO('sad23213s', new DateTime('now'));
        
        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $userRepository = $this->getMock(UserRepository::class);
        $userRepository->method('findByUsername')
            ->will($this->returnValue($user));
        
        $passwordValidator = $this->getMock(PasswordValidator::class);
        $passwordValidator->method('isPasswordValid')
            ->will($this->returnValue(true));
        
        $userTokenGenerator = $this->getMock(UserTokenGenerator::class);
        $userTokenGenerator->method('createTokenForUser')
            ->will($this->returnValue($authToken));
        
        $loginService = $this->getMockBuilder(StatelessLoginService::class)
            ->setConstructorArgs(array($userRepository, $passwordValidator, $userTokenGenerator))
            ->setMethods(array('findUserOrThrowException'))
            ->getMock();

        $returnedToken = $loginService->login('username', 'password');

        $this->assertEquals($authToken, $returnedToken);
    }
    
    public function testThrowExceptionIfUserNotFound()
    {
        $userRepository = $this->getMock(UserRepository::class);
        $userRepository->method('findByUsername')
            ->will($this->returnValue(null));
        
        $passwordValidator = $this->getMock(PasswordValidator::class);
        
        $userTokenGenerator = $this->getMock(UserTokenGenerator::class);
        $loginService = $this->getMockBuilder(StatelessLoginService::class)
            ->setConstructorArgs(array($userRepository, $passwordValidator, $userTokenGenerator))
            ->setMethods(array('findUserOrThrowException'))
            ->getMock();

        $this->setExpectedException(Exception::class);
        
        $returnedToken = $loginService->login('username', 'password');
    }
    
    public function testThrowExceptionIfInvalidPassword()
    {
        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $userRepository = $this->getMock(UserRepository::class);
        $userRepository->method('findByUsername')
            ->will($this->returnValue($user));
        
        $passwordValidator = $this->getMock(PasswordValidator::class);
        $passwordValidator->method('isPasswordValid')
            ->will($this->returnValue(false));
        
        $userTokenGenerator = $this->getMock(UserTokenGenerator::class);
        $loginService = $this->getMockBuilder(StatelessLoginService::class)
            ->setConstructorArgs(array($userRepository, $passwordValidator, $userTokenGenerator))
            ->setMethods(array('findUserOrThrowException'))
            ->getMock();

        $this->setExpectedException(Exception::class);
        
        $returnedToken = $loginService->login('username', 'password');
    }
}
