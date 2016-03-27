<?php
namespace Tests\Application\Command;

use Application\Command\Security\LoginCommand;
use Application\Dto\Security\AuthenticationTokenDTO;
use Application\Dto\Security\LoginDTO;
use Application\Service\Security\LoginService;
use DateTime;

class LoginCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecuteCommand()
    {
        $authToken = new AuthenticationTokenDTO(213123123123, new DateTime('now'));
        $statelessLoginService = $this->getMock(LoginService::class);
        $statelessLoginService->expects($this->once())
            ->method('login')
            ->will($this->returnValue($authToken));
        
        $login = $this->getMock(LoginDTO::class);
        $loginCommand = new LoginCommand($statelessLoginService);
        $returnedAuthToken = $loginCommand->execute($login);
        
        $this->assertEquals($authToken, $returnedAuthToken);
    }
}
