<?php
namespace Tests\Application\Command;

use Application\Command\LoginCommand;
use Application\Dto\AuthenticationToken;
use Application\Dto\Login;
use Application\Service\StatelessLoginService;
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
