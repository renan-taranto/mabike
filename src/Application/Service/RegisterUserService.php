<?php
namespace Application\Service;

use Application\Exception\ValidationFailedException;
use Application\Service\Validator\ValidatorInterface;
use Domain\Entity\Factory\UserFactory;
use Domain\Entity\Repository\UserRepository;
use Domain\Entity\User;
use Exception;

class RegisterUserService
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
