<?php
namespace Rtaranto\Application\Service\Security\Factory;

use Doctrine\ORM\EntityManager;
use Rtaranto\Application\Service\Security\BikerRegistrationService;
use Rtaranto\Application\Service\Security\UserRegistrationService;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\Factory\UserFactory;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Infrastructure\Repository\DoctrineUserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BikerRegistrationServiceFactory implements UserRegistrationServiceFactoryInterface
{
    private $em;
    private $userPasswordEncoder;
    private $sfValidator;
    
    public function __construct(EntityManager $em, UserPasswordEncoderInterface $userPasswordEncoder, ValidatorInterface $sfValidator)
    {
        $this->em = $em;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->sfValidator = $sfValidator;
    }
    
    public function createUserRegistrationService()
    {
        $userFactory = new UserFactory($this->userPasswordEncoder);
        $userRepository = new DoctrineUserRepository($this->em);
        $validator = new Validator($this->sfValidator);
        $userRegistrationService = new UserRegistrationService($userFactory, $userRepository, $validator);
        
        $bikerRepository = new DoctrineBikerRepository($this->em);
        return new BikerRegistrationService(
            $userRegistrationService,
            $bikerRepository,
            $validator
        );
    }
}
