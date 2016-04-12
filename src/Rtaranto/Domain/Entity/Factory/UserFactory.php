<?php
namespace Rtaranto\Domain\Entity\Factory;

use Rtaranto\Domain\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory implements UserFactoryInterface
{
    private $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    
    public function createUser($username, $email, $password, array $roles)
    {
        $user = new User($username, $email, $password, $roles);
        $encodedPass = $this->passwordEncoder->encodePassword($user, $password);
        $user->setPassword($encodedPass);
        return $user;
    }
}
