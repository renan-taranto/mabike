<?php
namespace Rtaranto\Application\EndpointAction\Factory\RearTireChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\PatchActionFactoryInterface;
use Rtaranto\Application\EndpointAction\PerformedMaintenance\PatchPerformedMaintenanceAction;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\PerformedMaintenance\PerformedMaintenancePatcher;
use Rtaranto\Application\Service\Validator\PerformedMaintenanceDTOValidator;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedMaintenanceRepository;
use Rtaranto\Presentation\Form\Maintenance\PerformedMaintenanceDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatchPerformedRearTireChangeActionFactory implements PatchActionFactoryInterface
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
        $parametersBinder = new ParametersBinder($this->formFactory, PerformedMaintenanceDTOType::class);
        $performedMaintenanceDtoValidator = new PerformedMaintenanceDTOValidator($this->sfValidator);
        $inputProcessor = new RequestParamsProcessor($parametersBinder, $performedMaintenanceDtoValidator);
        
        $motorcycleRepository = new DoctrineMotorcycleRepository($this->em);
        $performedRearTireChangeRepository = new DoctrinePerformedMaintenanceRepository(
            $this->em,
            PerformedRearTireChange::class
        );
        
        $validator = new Validator($this->sfValidator);
        $performedMaintenancePatcher = new PerformedMaintenancePatcher(
            $motorcycleRepository,
            $performedRearTireChangeRepository,
            $validator
        );
        
        return new PatchPerformedMaintenanceAction(
            $performedRearTireChangeRepository,
            $inputProcessor,
            $performedMaintenancePatcher
        );
    }
}
