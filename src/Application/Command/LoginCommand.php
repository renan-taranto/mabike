<?php
namespace Application\Command;

use Application\Dto\Login;
use Application\Service\StatelessLoginService;

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
