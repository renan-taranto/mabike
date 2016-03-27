<?php
namespace Application\Command\Security;

use Application\Dto\Security\UserRegistrationDTO;
use Application\Service\Security\UserRegistration;
use Domain\Entity\User;

class UserRegistrationCommand
{
    private $userRegistration;
    
    /**
     * @param UserRegistration $userRegistration
     */
    public function __construct(UserRegistration $userRegistration)
    {
        $this->userRegistration = $userRegistration;
    }
    
    /**
     * @param UserRegistrationDTO $registerUserDTO
     * @return User
     */
    public function execute(UserRegistrationDTO $registerUserDTO)
    {
        $user = $this->userRegistration->registerUser(
            $registerUserDTO->getUsername(),
            $registerUserDTO->getEmail(),
            $registerUserDTO->getPassword()
        );
        
        return $user;
    }
}
