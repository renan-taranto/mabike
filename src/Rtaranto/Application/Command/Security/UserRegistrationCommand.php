<?php
namespace Rtaranto\Application\Command\Security;

use Rtaranto\Application\Dto\Security\UserRegistrationDTO;
use Rtaranto\Application\Service\Security\UserRegistrationInterface;
use Rtaranto\Domain\Entity\User;

class UserRegistrationCommand
{
    private $userRegistration;
    
    /**
     * @param UserRegistrationInterface $userRegistration
     */
    public function __construct(UserRegistrationInterface $userRegistration)
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
