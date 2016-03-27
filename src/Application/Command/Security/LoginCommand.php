<?php
namespace Application\Command\Security;

use Application\Dto\Security\Login;
use Application\Service\Security\StatelessLoginService;

class LoginCommand
{
    private $statelessLoginService;
    
    public function __construct(StatelessLoginService $statelessLoginService)
    {
        $this->statelessLoginService = $statelessLoginService;
    }

    public function execute(Login $login)
    {
        return $this->statelessLoginService->login(
            $login->getUsername(),
            $login->getPassword());
    }
}
