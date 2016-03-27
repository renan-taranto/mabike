<?php
namespace Tests\Application\Command;

use Application\Command\Security\LoginCommand;
use Application\Dto\Security\AuthenticationToken;
use Application\Dto\Security\Login;
use Application\Service\Security\StatelessLoginService;
use DateTime;

class LoginCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecuteCommand()
    {
        $authToken = new AuthenticationToken(213123123123, new DateTime('now'));
        $statelessLoginService = $this->getMockBuilder(StatelessLoginService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $statelessLoginService->expects($this->once())
            ->method('login')
            ->will($this->returnValue($authToken));
        
        $login = $this->getMock(Login::class);
        $loginCommand = new LoginCommand($statelessLoginService);
        $returnedAuthToken = $loginCommand->execute($login);
        
        $this->assertEquals($authToken, $returnedAuthToken);
    }
}
