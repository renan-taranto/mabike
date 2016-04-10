<?php
namespace Tests\Rtaranto\Application\Service\Security\Factory;

use DateInterval;
use DateTime;
use InvalidArgumentException;
use Rtaranto\Application\Service\Security\Factory\AuthenticationTokenFactory;

class AuthenticationTokenFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccesfullyCreateAuthenticationToken()
    {
        $tokenLength = 148;
        $minutesBeforeExpiration = 12;
        $factory = new AuthenticationTokenFactory();
        $authTokenDTO = $factory->create($tokenLength, $minutesBeforeExpiration);
        
        $expectedDateTime = new DateTime('now');
        $expectedDateTime->add(new DateInterval('PT' . $minutesBeforeExpiration . 'M'));
        
        $this->assertEquals($tokenLength, strlen($authTokenDTO->getKey()));
        $this->assertEquals($expectedDateTime, $authTokenDTO->getExpirationDateTime());
    }
    
    public function testInvalidMinutesBeforeExpiration()
    {
        $factory = new AuthenticationTokenFactory();
        
        $this->setExpectedException(InvalidArgumentException::class);
        $factory->create(123, -1);
    }
}
