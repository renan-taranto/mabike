<?php
namespace Tests\Rtaranto\Application\Service;

use Rtaranto\Application\Dto\Security\AuthenticationTokenDTO;
use Rtaranto\Application\Factory\AuthenticationTokenFactory;
use Rtaranto\Application\Service\Security\UserTokenService;
use Rtaranto\Domain\Entity\Repository\UserRepository;
use Rtaranto\Domain\Entity\User;
use DateTime;

class UserTokenServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyTokenCreation()
    {
        $key = 'z2a1$3A12';
        $dateTime = new DateTime('now');
        $authToken =  new AuthenticationTokenDTO($key, $dateTime);
        
        $userRepository = $this->getMock(UserRepository::class);
        
        $tokenFactory = $this->getMockBuilder(AuthenticationTokenFactory::class)
            ->getMock();
        $tokenFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($authToken));
                
        $userTokenService = $this->getMockBuilder(UserTokenService::class)
            ->setMethods(array('saveChanges'))
            ->setConstructorArgs(array($userRepository, $tokenFactory))
            ->getMock();
        
        $user = new User('username', 'email', 'password');
        $userTokenService->createTokenForUser($user);
        
        $this->assertEquals($key, $user->getApiKey());
        $this->assertEquals($dateTime, $user->getApiKeyExpirationTime());
    }
}
