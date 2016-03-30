<?php
namespace Tests\Rtaranto\Application\Command;

use Rtaranto\Application\Command\Security\LoginCommand;
use Rtaranto\Application\Dto\Security\AuthenticationTokenDTO;
use Rtaranto\Application\Dto\Security\LoginDTO;
use Rtaranto\Application\Service\Security\LoginServiceInterface;
use DateTime;

class LoginCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecuteCommand()
    {
        $authToken = new AuthenticationTokenDTO(213123123123, new DateTime('now'));
        $statelessLoginService = $this->getMock(LoginServiceInterface::class);
        $statelessLoginService->expects($this->once())
            ->method('login')
            ->will($this->returnValue($authToken));
        
        $login = $this->getMock(LoginDTO::class);
        $loginCommand = new LoginCommand($statelessLoginService);
        $returnedAuthToken = $loginCommand->execute($login);
        
        $this->assertEquals($authToken, $returnedAuthToken);
    }
}
