<?php
namespace Application\Command;

use Application\Dto\UserRegistration;
use Application\Service\RegisterUserService;
use Domain\Entity\User;

class UserRegistrationCommand
{
    private $registerUserService;
    
    /**
     * @param RegisterUserService $registerUserService
     */
    public function __construct(RegisterUserService $registerUserService)
    {
        $this->registerUserService = $registerUserService;
    }
    
    /**
     * @param UserRegistration $registerUserDTO
     * @return User
     */
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
