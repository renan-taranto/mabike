<?php
namespace Rtaranto\Application\Service\Security;

use Exception;
use Rtaranto\Application\Dto\Security\AuthenticationTokenDTO;
use Rtaranto\Application\Service\Security\PasswordValidatorInterface;
use Rtaranto\Application\Service\Security\TokenGeneratorInterface;
use Rtaranto\Domain\Entity\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class StatelessLoginService implements LoginServiceInterface
{
    private $userRepository;
    private $passwordValidatorService;
    private $userTokenGenerator;
    
    public function __construct(
        UserRepositoryInterface $userRepository,
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
     * @throws BadCredentialsException
     * @return AuthenticationTokenDTO
     */
    public function login($username, $password)
    {
        $user = $this->findUserOrThrowBadCredentias($username);
        
        if (!$this->passwordValidatorService->isPasswordValid($user, $password)) {
            throw new BadCredentialsException('Invalid password.');
        }
        
        return $this->userTokenGenerator->createTokenForUser($user);
    }
    
    /**
     * @param string $username
     * @param string $password
     * @throws BadCredentialsException
     */
    private function findUserOrThrowBadCredentias($username)
    {
        $user = $this->userRepository->findByUsername($username);
        if (!empty($user)) {
            return $user;
        }
        throw new BadCredentialsException('User not found');
    }
}
