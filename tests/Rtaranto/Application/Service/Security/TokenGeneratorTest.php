<?php
namespace Tests\Rtaranto\Application\Service\Security;

use DateTime;
use Rtaranto\Application\Dto\Security\AuthenticationTokenDTO;
use Rtaranto\Application\Service\Security\Factory\AuthenticationTokenFactoryInterface;
use Rtaranto\Application\Service\Security\TokenGenerator;
use Rtaranto\Domain\Entity\Repository\UserRepositoryInterface;
use Rtaranto\Domain\Entity\User;

class TokenGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessfullyTokenCreation()
    {
        $key = 'z2a1$3A12';
        $dateTime = new DateTime('now');
        $authToken =  new AuthenticationTokenDTO($key, $dateTime);
        
        $userRepository = $this->getMock(UserRepositoryInterface::class);
        
        $tokenFactory = $this->getMockBuilder(AuthenticationTokenFactoryInterface::class)
            ->getMock();
        $tokenFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($authToken));
                
        $userTokenService = $this->getMockBuilder(TokenGenerator::class)
            ->setMethods(array('saveChanges'))
            ->setConstructorArgs(array($userRepository, $tokenFactory))
            ->getMock();
        
        $user = new User('username', 'email', 'password', array(User::ROLE_USER));
        $userTokenService->createTokenForUser($user);
        
        $this->assertEquals($key, $user->getApiKey());
        $this->assertEquals($dateTime, $user->getApiKeyExpirationTime());
    }
}
