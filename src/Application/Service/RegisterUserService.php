<?php
namespace Application\Service;

use Domain\Entity\Repository\UserRepository;
use Domain\Entity\User;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterUserService
{
    private $userRepository;
    private $validator;
    
    public function __construct(
        UserRepository $userRepository,
        ValidatorInterface $validator
    ) {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }
    
    /**
     * @param User $user
     * @return User The registered User
     * @throws Exception
     */
    public function registerUser(User $user)
    {
        $errors = $this->validator->validate($user);
        if (count($errors)) {
            throw new Exception($errors[0]->getMessage());
        }
        return $this->userRepository->addUser($user);
    }
}
