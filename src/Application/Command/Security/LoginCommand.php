<?php
namespace Application\Command\Security;

use Application\Dto\Security\LoginDTO;
use Application\Service\Security\LoginService;

class LoginCommand
{
    private $loginService;
    
    public function __construct(LoginService $statelessLoginService)
    {
        $this->loginService = $statelessLoginService;
    }

    public function execute(LoginDTO $login)
    {
        return $this->loginService->login(
            $login->getUsername(),
            $login->getPassword());
    }
}
