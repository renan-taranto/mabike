<?php
namespace Rtaranto\Application\Service\Security;

use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Rtaranto\Domain\Entity\User;

class BikerRegistrationService implements BikerRegistrationServiceInterface
{
    /**
     * @var UserRegistrationInterface
     */
    private $userRegistrationService;
    
    /**
     * @var BikerRepositoryInterface
     */
    private $bikerRepository;
    
    /**
     * @var ValidatorInterface
     */
    private $validator;
    
    public function __construct(
        UserRegistrationInterface $userRegistrationService,
        BikerRepositoryInterface $bikerRepository,
        ValidatorInterface $validator
    ) {
        $this->userRegistrationService = $userRegistrationService;
        $this->bikerRepository = $bikerRepository;
        $this->validator = $validator;
    }
    
    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @return Biker
     */
    public function registerBiker($username, $email, $password)
    {
        $user = $this->userRegistrationService
            ->registerUser($username, $email, $password, array(User::ROLE_BIKER));
        
        $biker = new Biker($username, $email, $user);
        $this->validator->throwValidationFailedIfNotValid($biker);
        
        return $this->bikerRepository->add($biker);
    }

}
