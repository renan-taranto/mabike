<?php
namespace Application\Command;

use Application\Dto\UserRegistration;
use Application\Service\RegisterUserService;

class RegisterUserCommand
{
    private $registerUserService;
    
    public function __construct(RegisterUserService $registerUserService)
    {
        $this->registerUserService = $registerUserService;
    }
    
    public function execute(UserRegistration $registerUserDTO)
    {
        $user = $this->registerUserService->registerUser(
            $registerUserDTO->getUsername(),
            $registerUserDTO->getEmail(),
            $registerUserDTO->getPassword()
        );
        
        return $user;
    }
}
