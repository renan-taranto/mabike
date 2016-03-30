<?php
namespace Rtaranto\Application\Service\Security;

use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Factory\UserFactory;
use Rtaranto\Domain\Entity\Repository\UserRepository;
use Rtaranto\Domain\Entity\User;
use Exception;

class UserRegistrationService implements UserRegistration
{
    private $userRepository;
    private $validator;
    private $userFactory;
    
    public function __construct(
        UserFactory $userFactory,
        UserRepository $userRepository,
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
     * @throws Exception
     */
    public function registerUser($username, $email, $password)
    {
        $user = $this->userFactory->createUser($username, $email, $password);
        
        if (!$this->validator->isValid($user)) {
            throw new ValidationFailedException($this->validator->getErrors($user));
        }
        
        return $this->userRepository->addUser($user);
    }
}
