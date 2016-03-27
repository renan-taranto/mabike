<?php

namespace Tests\Application\Service;

use Application\Dto\Security\AuthenticationTokenDTO;
use Application\Factory\AuthenticationTokenFactory;
use Application\Service\Security\UserTokenService;
use DateTime;
use Domain\Entity\Repository\UserRepository;
use Domain\Entity\User;

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
