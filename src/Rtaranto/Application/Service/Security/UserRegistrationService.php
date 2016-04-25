<?php
namespace Rtaranto\Application\Service\Security;

use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Factory\UserFactoryInterface;
use Rtaranto\Domain\Entity\Repository\UserRepositoryInterface;
use Rtaranto\Domain\Entity\User;

class UserRegistrationService implements UserRegistrationInterface
{
    private $userRepository;
    private $validator;
    private $userFactory;
    
    public function __construct(
        UserFactoryInterface $userFactory,
        UserRepositoryInterface $userRepository,
        ValidatorInterface $validator
    ) {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }
    
    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     * @throws ValidationFailedException
     */
    public function registerUser($username, $email, $password, array $roles)
    {
        $user = $this->userFactory->createUser($username, $email, $password, $roles);
        
        $this->validator->throwValidationFailedIfNotValid($user);
        
        return $this->userRepository->addUser($user);
    }
}
