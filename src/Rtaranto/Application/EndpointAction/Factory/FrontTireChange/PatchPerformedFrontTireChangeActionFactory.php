<?php
namespace Rtaranto\Application\EndpointAction\Factory\FrontTireChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\PatchActionFactoryInterface;
use Rtaranto\Application\EndpointAction\PerformedMaintenance\PatchPerformedMaintenanceAction;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\PerformedMaintenance\PerformedMaintenancePatcher;
use Rtaranto\Application\Service\Validator\PerformedMaintenanceDTOValidator;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedMaintenanceRepository;
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
        
        return new PatchPerformedMaintenanceAction(
            $performedFrontTireChangeRepository,
            $requestParamsProcessor,
            $performedFrontTireChangePatcher
        );
    }
    
    private function createPerformedFrontTireChangeRepository()
    {
        return new DoctrinePerformedMaintenanceRepository($this->em, PerformedFrontTireChange::class);
    }
    
    private function createFrontTireChangeRepository()
    {
        return new DoctrineMaintenanceRepository($this->em, FrontTireChange::class);
    }
    
    private function createRequestParamsProcessor()
    {
        $parametersBinder = new ParametersBinder($this->formFactory, PerformedMaintenanceDTOType::class);
        $validator = $this->createPerformedMaintenanceDTOValidator();
        return new RequestParamsProcessor($parametersBinder, $validator);
    }
    
    private function createPerformedFrontTireChangePatcher()
    {
        $motorcycleRepository = new DoctrineMotorcycleRepository($this->em);
        $performedFrontTireChangeRepository = $this->createPerformedFrontTireChangeRepository();
        $validator = $this->createValidator();
        
        return new PerformedMaintenancePatcher(
            $motorcycleRepository,
            $performedFrontTireChangeRepository,
            $validator
        );
    }
    
    private function createValidator()
    {
        return new Validator($this->sfValidator);
    }
    
    private function createPerformedMaintenanceDTOValidator()
    {
        return new PerformedMaintenanceDTOValidator($this->sfValidator);
    }
}

