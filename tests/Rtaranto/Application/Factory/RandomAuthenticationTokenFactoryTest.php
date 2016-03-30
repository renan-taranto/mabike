<?php
namespace Tests\Rtaranto\Application\Factory;

use Rtaranto\Application\Factory\RandomAuthenticationTokenFactory;
use DateInterval;
use DateTime;
use InvalidArgumentException;

class RandomAuthenticationTokenFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccesfullyCreateAuthenticationToken()
    {
        $tokenLength = 148;
        $minutesBeforeExpiration = 12;
        $factory = new RandomAuthenticationTokenFactory();
        $authTokenDTO = $factory->create($tokenLength, $minutesBeforeExpiration);
        
        $expectedDateTime = new DateTime('now');
        $expectedDateTime->add(new DateInterval('PT' . $minutesBeforeExpiration . 'M'));
        
        $this->assertEquals($tokenLength, strlen($authTokenDTO->getKey()));
        $this->assertEquals($expectedDateTime, $authTokenDTO->getExpirationDateTime());
    }
    
    public function testInvalidMinutesBeforeExpiration()
    {
        $factory = new RandomAuthenticationTokenFactory();
        
        $this->setExpectedException(InvalidArgumentException::class);
        $factory->create(123, -1);
    }
}
