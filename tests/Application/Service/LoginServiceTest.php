<?php

namespace Tests\Application\Service;

use Application\Service\LoginService;
use Domain\Entity\Repository\UserRepository;
use Domain\Entity\User;
use Infrastructure\Security\TokenGenerator;
use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class PasswordEncoder extends BasePasswordEncoder
{
    public function encodePassword($raw, $salt)
    {
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
    }
}

class LoginServiceTest extends \PHPUnit_Framework_TestCase
{
    
    
    public function testUserNotFound()
    {
        $userRepository = $this
                ->getMockBuilder(UserRepository::class)
                ->disableOriginalConstructor()
                ->getMock();
        $userRepository->expects($this->once())
                ->method('findByUsername')
                ->will($this->returnValue(null));

        $encoderFactory = $this
                ->getMockBuilder(EncoderFactory::class)
                ->disableOriginalConstructor()
                ->getMock();

        $tokenGenerator = new TokenGenerator($userRepository);

        $loginService = new LoginService($userRepository, $encoderFactory, $tokenGenerator);
        $this->setExpectedException('Symfony\Component\Security\Core\Exception\BadCredentialsException');
        $loginService->createUserToken('dasdas', 'asdasd');
    }

    public function testInvalidPassword()
    {
        $userRepository = $this
                ->getMockBuilder(UserRepository::class)
                ->disableOriginalConstructor()
                ->getMock();
        $user = new User('renan', '12345', array('USER'));
        $userRepository->expects($this->once())
                ->method('findByUsername')
                ->will($this->returnValue($user));

        $encoder = $this
                ->getMockBuilder(PasswordEncoder::class)
                ->disableOriginalConstructor()
                ->getMock();
        $encoder->expects($this->once())
                ->method('isPasswordValid')
                ->will($this->returnValue(false));
        
        $encoderFactory = $this
                ->getMockBuilder(EncoderFactory::class)
                ->disableOriginalConstructor()
                ->getMock();
        $encoderFactory->expects($this->once())
                ->method('getEncoder')
                ->will($this->returnValue($encoder));
        
        

        $tokenGenerator = new TokenGenerator($userRepository);
        $loginService = new LoginService($userRepository, $encoderFactory, $tokenGenerator);
        $this->setExpectedException('Symfony\Component\Security\Core\Exception\BadCredentialsException');
        $loginService->createUserToken('renadasn', '1234dsa5');
    }

}
