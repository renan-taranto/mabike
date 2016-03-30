<?php
namespace Rtaranto\Application\Service\Security;

use Rtaranto\Application\Service\Security\PasswordValidatorInterface;
use Rtaranto\Domain\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class SaltedPasswordValidator implements PasswordValidatorInterface
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
