<?php
namespace Infrastructure\Security;

use Application\Security\PasswordValidator;
use Domain\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class SaltedPasswordValidator implements PasswordValidator
{
    private $encoderFactory;
    
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }
    
    public function isPasswordValid(User $user, $password)
    {
        $passwordEncoder = $this->encoderFactory->getEncoder($user);
        return $passwordEncoder->isPasswordValid(
            $user->getPassword(),
            $password,
            $user->getSalt());
    }
}
