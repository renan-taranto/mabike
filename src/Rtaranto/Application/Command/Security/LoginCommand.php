<?php
namespace Rtaranto\Application\Command\Security;

use Rtaranto\Application\Dto\Security\LoginDTO;
use Rtaranto\Application\Service\Security\LoginServiceInterface;

class LoginCommand
{
    private $loginService;
    
    public function __construct(LoginServiceInterface $statelessLoginService)
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
