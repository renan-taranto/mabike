<?php
namespace Tests\AppBundle\Security;

use AppBundle\Repository\UserRepository;
use AppBundle\Security\TokenGenerator;
use DateInterval;
use DateTime;

class TokenGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $userRepository = $this
            ->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $userRepository->expects($this->once())
            ->method('findByApiKey')
            ->will($this->returnValue(array()));
        
        $tokenGenerator = new TokenGenerator($userRepository);
        $token = $tokenGenerator->generate(123, 30);
        
        $this->assertEquals(123, strlen($token->getKey()));
        $expectedDateTime = new DateTime('now');
        $expectedDateTime->add(new DateInterval('PT' . 30 . 'M'));
        
        $this->assertEquals($expectedDateTime, $token->getExpirationDateTime());
    }
    
    public function testInvalidLength()
    {
        $userRepository = $this
            ->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $tokenGenerator = new TokenGenerator($userRepository);
        
        $this->setExpectedException('InvalidArgumentException');
        $tokenGenerator->generate(0, 10);
    }
    
    public function testInvalidMinutesBeforeExpiration()
    {
        $userRepository = $this
            ->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $tokenGenerator = new TokenGenerator($userRepository);
        
        $this->setExpectedException('InvalidArgumentException');
        $tokenGenerator->generate(10, 0);
    }
}
