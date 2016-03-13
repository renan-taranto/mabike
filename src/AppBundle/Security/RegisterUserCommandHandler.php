<?php
namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterUserCommandHandler
{
    private $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function perform(RegisterUserCommand $registerUserCommand)
    {
        $user = new User(
            $registerUserCommand->getUsername(),
            $registerUserCommand->getEmail(),
            array('ROLE_USER')
        );
        
        $password = $this->passwordEncoder
            ->encodePassword($user, $registerUserCommand->getPassword());
        $user->setPassword($password);
        
        return $user;
    }
}
