<?php
namespace AppBundle\Security;

use Domain\Entity\Repository\UserRepository;
use Domain\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LoginService
{
    private $userRepository;
    private $encoderFactory;
    private $tokenGenerator;
    
    public function __construct(
        UserRepository $userRepository,
        EncoderFactoryInterface $encoderFactory,
        TokenGenerator $tokenGenerator
    ) {
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
        $this->tokenGenerator = $tokenGenerator;
    }
    
    /**
     * @param string $username
     * @param string $password
     * @return TokenCommand
     * @throws BadCredentialsException
     */
    public function createUserToken($username, $password)
    {
        $user = $this->findUserOrThrowBadCredentials($username);
        $this->throwBadCredentialsIfInvalidPassword($user, $password);
        
        $token = $this->tokenGenerator->generate();
        $this->persistUserToken($user, $token);
        return $token;
    }
    
    /**
     * @param string $username
     * @return User
     * @throws BadCredentialsException
     */
    private function findUserOrThrowBadCredentials($username)
    {
        $user = $this->userRepository->findByUserName($username);
        if (empty($user)) {
            throw new BadCredentialsException('Username could not be found.');
        }
        return $user;
    }
    
    /**
     * @param string $encoded
     * @param string $rawPassword
     * @param string $salt
     * @return boolean
     */
    private function throwBadCredentialsIfInvalidPassword(User $user, $rawPassword)
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        if (!$encoder->isPasswordValid($user->getPassword(), $rawPassword, $user->getSalt())) {
            throw new BadCredentialsException('Invalid Password.');
        }
    }
    
    private function persistUserToken(User $user, TokenCommand $token)
    {
        $user->setApiKey($token->getKey());
        $user->setApiKeyExpirationTime($token->getExpirationDateTime());
        $this->userRepository->save($user);
    }
}
