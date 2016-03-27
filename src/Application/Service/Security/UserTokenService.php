<?php
namespace Application\Service\Security;

use Application\Dto\Security\AuthenticationToken;
use Application\Factory\AuthenticationTokenFactory;
use Application\Service\Security\UserTokenGenerator;
use Domain\Entity\Repository\UserRepository;
use Domain\Entity\User;

class UserTokenService implements UserTokenGenerator
{
    private static $KEY_LENGTH = 255;
    private static $MINUTES_BEFORE_EXPIRATION = 15;
    private $userRepository;
    private $authenticationTokenFactory;
    
    /**
     * @param UserRepository $userRepository
     * @param AuthenticationTokenFactory $authenticationTokenFactory
     */
    public function __construct(
        UserRepository $userRepository,
        AuthenticationTokenFactory $authenticationTokenFactory
    ) {
        $this->userRepository = $userRepository;
        $this->authenticationTokenFactory = $authenticationTokenFactory;
    }
    
    /**
     * Creates a random token for the given user and persists it
     * @param User $user
     * @return AuthenticationToken
     */
     
    public function createTokenForUser(User $user)
    {
        $token = $this->createToken();
        $user->updateApiKey($token->getKey(), $token->getExpirationDateTime());
        $this->saveChanges($user);
        return $token;
    }
    
    /**
     * @return AuthenticationToken
     */
    private function createToken()
    {
        return $this->authenticationTokenFactory->create(
            self::$KEY_LENGTH,
            self::$MINUTES_BEFORE_EXPIRATION
        );
    }
    
    /**
     * @param User $user
     */
    private function saveChanges(User $user)
    {
        $this->userRepository->updateUser($user);
    }
}
