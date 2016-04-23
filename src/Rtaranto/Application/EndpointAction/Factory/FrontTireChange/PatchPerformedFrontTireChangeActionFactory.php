<?php
namespace Rtaranto\Application\EndpointAction\Factory\FrontTireChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\PatchActionFactoryInterface;
use Rtaranto\Application\EndpointAction\FrontTireChange\PatchPerformedFrontTireChangeAction;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Maintenance\TireChange\PerformedFrontTireChangePatcher;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Infrastructure\Repository\DoctrineFrontTireChangeRepository;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedFrontTireChangeRepository;
use Rtaranto\Presentation\Form\Maintenance\PerformedMaintenanceDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatchPerformedFrontTireChangeActionFactory implements PatchActionFactoryInterface
{
    private $formFactory;
    private $sfValidator;
    private $em;
    
    public function __construct(
        FormFactoryInterface $formFactory,
        ValidatorInterface $sfValidator,
        EntityManagerInterface $em
    ) {
        $this->formFactory = $formFactory;
        $this->sfValidator = $sfValidator;
        $this->em = $em;
    }
    
    public function createPatchAction()
    {
        $performedFrontTireChangeRepository = $this->createPerformedFrontTireChangeRepository();
        $requestParamsProcessor = $this->createRequestParamsProcessor();
        $performedFrontTireChangePatcher = $this->createPerformedFrontTireChangePatcher();
        
        return new PatchPerformedFrontTireChangeAction(
            $performedFrontTireChangeRepository,
            $requestParamsProcessor,
            $performedFrontTireChangePatcher
        );
    }
    
    private function createPerformedFrontTireChangeRepository()
    {
        return new DoctrinePerformedFrontTireChangeRepository($this->em);
    }
    
    private function createFrontTireChangeRepository()
    {
        return new DoctrineFrontTireChangeRepository($this->em);
    }
    
    private function createRequestParamsProcessor()
    {
        $parametersBinder = new ParametersBinder($this->formFactory, PerformedMaintenanceDTOType::class);
        $validator = $this->createValidator();
        return new RequestParamsProcessor($parametersBinder, $validator);
    }
    
    private function createPerformedFrontTireChangePatcher()
    {
        $frontTireChangeRepository = $this->createFrontTireChangeRepository();
        $performedFrontTireChangeRepository = $this->createPerformedFrontTireChangeRepository();
        $validator = $this->createValidator();
        return new PerformedFrontTireChangePatcher(
            $frontTireChangeRepository,
            $performedFrontTireChangeRepository,
            $validator
        );
    }
    
    private function createValidator()
    {
        return new Validator($this->sfValidator);
    }
}

