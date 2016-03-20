<?php
namespace Application\Service;

use Domain\Entity\Factory\UserFactory;
use Domain\Entity\Repository\UserRepository;
use Domain\Entity\User;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        $errors = $this->validator->validate($user);
        if (count($errors)) {
            throw new Exception($errors[0]->getMessage());
        }
        return $this->userRepository->addUser($user);
    }
}
