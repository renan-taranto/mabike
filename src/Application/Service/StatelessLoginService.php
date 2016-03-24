<?php
namespace Application\Service;

use Application\Security\PasswordValidator;
use Application\Security\UserTokenGenerator;
use Domain\Entity\Repository\UserRepository;
use Exception;

class StatelessLoginService
{
    private $userRepository;
    private $passwordValidatorService;
    private $userTokenGenerator;
    
    public function __construct(
        UserRepository $userRepository,
        PasswordValidator $passwordValidator,
        UserTokenGenerator $userTokenGenerator
    ) {
        $this->userRepository = $userRepository;
        $this->passwordValidatorService = $passwordValidator;
        $this->userTokenGenerator = $userTokenGenerator;
    }
    /**
     * @param string $username
     * @param string $password
     */
    public function login($username, $password)
    {
        $user = $this->findUserOrThrowException($username);
        
        if (!$this->passwordValidatorService->isPasswordValid($user, $password)) {
            throw new Exception('Invalid password.');
        }
        
        return $this->userTokenGenerator->createTokenForUser($user);
    }
    
    /**
     * @param string $username
     * @param string $password
     */
    private function findUserOrThrowException($username)
    {
        $user = $this->userRepository->findByUsername($username);
        if (!empty($user)) {
            return $user;
        }
        throw new Exception('User not found');
    }
}
