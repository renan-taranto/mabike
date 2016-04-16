<?php
namespace Rtaranto\Application\EndpointAction\Factory\Motorcycle;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\PatchActionFactoryInterface;
use Rtaranto\Application\EndpointAction\Motorcycle\PatchMotorcycleAction;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Motorcycle\MotorcyclePatcher;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Rtaranto\Presentation\Form\Motorcycle\MotorcycleDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatchMotorcycleActionFactory implements PatchActionFactoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    
    /**
     * @var ValidatorInterface
     */
    private $sfValidator;
    
    /**
     * @param UserInterface $user
     * @param EntityManagerInterface $em
     * @param FormFactoryInterface $formFactory
     * @param ValidatorInterface $sfValidator
     */
    public function __construct(
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        ValidatorInterface $sfValidator
    ) {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->sfValidator = $sfValidator;
    }

    /**
     * @return PatchMotorcycleAction
     */
    public function createPatchAction()
    {
        $parametersBinder = new ParametersBinder($this->formFactory, MotorcycleDTOType::class);
        $validator = new Validator($this->sfValidator);
        
        $inputProcessor = new RequestParamsProcessor($parametersBinder, $validator);
        
        $motorcycleRepository = new DoctrineMotorcycleRepository($this->em);
        $motorcyclePatcher = new MotorcyclePatcher($motorcycleRepository, $validator);
        
        return new PatchMotorcycleAction($inputProcessor, $motorcyclePatcher, $motorcycleRepository);
    }
}
