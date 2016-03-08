<?php
namespace AppBundle\Security;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Exception;
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
        $user = $this->findUserOrThrowException($username);
        if (!$this->isPasswordValid($user, $password)) {
            throw new BadCredentialsException('Invalid Password.');
            
        }
        
        $token = $this->tokenGenerator->generate();
        $this->persistUserToken($user, $token);
        return $token;
    }
    
    /**
     * @param string $username
     * @return User
     * @throws BadCredentialsException
     */
    private function findUserOrThrowException($username)
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
    private function isPasswordValid(User $user, $rawPassword)
    {
        $encoder = $this->encoderFactory->getEncoder($user);
        return $encoder->isPasswordValid($user->getPassword(), $rawPassword, $user->getSalt());
    }
    
    private function persistUserToken(User $user, TokenCommand $token)
    {
        $user->setApiKey($token->getKey());
        $user->setApiKeyExpirationTime($token->getExpirationDateTime());
        $this->userRepository->save($user);
    }
}
