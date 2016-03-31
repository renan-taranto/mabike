<?php
namespace Tests\Rtaranto\Application\Service;

use Rtaranto\Application\Dto\Security\AuthenticationTokenDTO;
use Rtaranto\Application\Service\Security\PasswordValidatorInterface;
use Rtaranto\Application\Service\Security\TokenGeneratorInterface;
use Rtaranto\Application\Service\Security\StatelessLoginService;
use Rtaranto\Domain\Entity\Repository\UserRepositoryInterface;
use Rtaranto\Domain\Entity\User;
use DateTime;
use Exception;

class StatelessLoginServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullLogin()
    {
        $authToken = new AuthenticationTokenDTO('sad23213s', new DateTime('now'));
        
        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $userRepository = $this->getMock(UserRepositoryInterface::class);
        $userRepository->method('findByUsername')
            ->will($this->returnValue($user));
        
        $passwordValidator = $this->getMock(PasswordValidatorInterface::class);
        $passwordValidator->method('isPasswordValid')
            ->will($this->returnValue(true));
        
        $userTokenGenerator = $this->getMock(TokenGeneratorInterface::class);
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
        $userRepository = $this->getMock(UserRepositoryInterface::class);
        $userRepository->method('findByUsername')
            ->will($this->returnValue(null));
        
        $passwordValidator = $this->getMock(PasswordValidatorInterface::class);
        
        $userTokenGenerator = $this->getMock(TokenGeneratorInterface::class);
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
        $userRepository = $this->getMock(UserRepositoryInterface::class);
        $userRepository->method('findByUsername')
            ->will($this->returnValue($user));
        
        $passwordValidator = $this->getMock(PasswordValidatorInterface::class);
        $passwordValidator->method('isPasswordValid')
            ->will($this->returnValue(false));
        
        $userTokenGenerator = $this->getMock(TokenGeneratorInterface::class);
        $loginService = $this->getMockBuilder(StatelessLoginService::class)
            ->setConstructorArgs(array($userRepository, $passwordValidator, $userTokenGenerator))
            ->setMethods(array('findUserOrThrowException'))
            ->getMock();

        $this->setExpectedException(Exception::class);
        
        $returnedToken = $loginService->login('username', 'password');
    }
}
