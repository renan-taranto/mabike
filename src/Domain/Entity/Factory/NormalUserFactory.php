<?php
namespace Domain\Entity\Factory;

use Domain\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class NormalUserFactory implements UserFactory
{
    private $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function createUser($username, $email, $password)
    {
        $user = new User($username, $email, $password);
        $encodedPass = $this->passwordEncoder->encodePassword($user, $password);
        $user->setPassword($encodedPass);
        $user->addRole('ROLE_USER');
        return $user;
    }
}
