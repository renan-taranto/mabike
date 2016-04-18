<?php
namespace Rtaranto\Application\EndpointAction\Factory\Motorcycle;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\PostActionFactoryInterface;
use Rtaranto\Application\EndpointAction\Motorcycle\PostMotorcycleAction;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Motorcycle\MotorcycleRegistrationService;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\User;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Presentation\Form\Motorcycle\MotorcycleDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostMotorcycleActionFactory implements PostActionFactoryInterface
{
    private $em;
    private $formFactory;
    private $sfValidator;
    private $user;
    
    public function __construct(
        User $user,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        ValidatorInterface $sfValidator
    ) {
        $this->user = $user;
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->sfValidator = $sfValidator;
    }
    
    /**
     * @return PostMotorcycleAction
     */
    public function createPostAction()
    {
        $validator = new Validator($this->sfValidator);
        $motorcycleRegistration = new MotorcycleRegistrationService($validator, $this->em);
        
        $parametersBinder = new ParametersBinder($this->formFactory, MotorcycleDTOType::class);
        $inputProcessor = new RequestParamsProcessor($parametersBinder, $validator);
        
        $bikerRepository = new DoctrineBikerRepository($this->em);
        $biker = $bikerRepository->findOneByUser($this->user);
        
        return new PostMotorcycleAction($biker, $motorcycleRegistration, $inputProcessor);
    }
}
