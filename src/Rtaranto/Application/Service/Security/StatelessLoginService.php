<?php
namespace Rtaranto\Application\Service\Security;

use Rtaranto\Application\Dto\Security\AuthenticationTokenDTO;
use Rtaranto\Application\Service\Security\PasswordValidatorInterface;
use Rtaranto\Application\Service\Security\TokenGeneratorInterface;
use Rtaranto\Domain\Entity\Repository\UserRepository;
use Exception;

class StatelessLoginService implements LoginServiceInterface
{
    private $userRepository;
    private $passwordValidatorService;
    private $userTokenGenerator;
    
    public function __construct(
        UserRepository $userRepository,
        PasswordValidatorInterface $passwordValidator,
        TokenGeneratorInterface $userTokenGenerator
    ) {
        $this->userRepository = $userRepository;
        $this->passwordValidatorService = $passwordValidator;
        $this->userTokenGenerator = $userTokenGenerator;
    }
    /**
     * @param type $username
     * @param type $password
     * @return AuthenticationTokenDTO
     * @throws Exception
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
