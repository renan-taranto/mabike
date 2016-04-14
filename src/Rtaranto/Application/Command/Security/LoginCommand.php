<?php
namespace Rtaranto\Application\Command\Security;

use Rtaranto\Application\Dto\Security\AuthenticationTokenDTO;
use Rtaranto\Application\Dto\Security\LoginDTO;
use Rtaranto\Application\Service\Security\LoginServiceInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LoginCommand
{
    /**
     * @var LoginServiceInterface 
     */
    private $loginService;
    
    /**
     * @param LoginServiceInterface $loginService
     */
    public function __construct(LoginServiceInterface $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     * @param LoginDTO $loginDTO
     * @return AuthenticationTokenDTO
     * @throws BadCredentialsException
     */
    public function execute(LoginDTO $loginDTO)
    {
        return $this->loginService->login(
            $loginDTO->getUsername(),
            $loginDTO->getPassword()
        );
    }
}
