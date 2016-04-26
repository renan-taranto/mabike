<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\PatchActionFactoryInterface;
use Rtaranto\Application\EndpointAction\PerformedMaintenance\PatchPerformedMaintenanceAction;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\PerformedMaintenance\PerformedMaintenancePatcher;
use Rtaranto\Application\Service\Validator\PerformedMaintenanceDTOValidator;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedMaintenanceRepository;
use Rtaranto\Presentation\Form\Maintenance\PerformedMaintenanceDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatchPerformedOilChangeActionFactory implements PatchActionFactoryInterface
{
    private $em;
    private $sfValidator;
    private $formFactory;
    
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
        $performedMaintenanceDTOValidator = new PerformedMaintenanceDTOValidator($this->sfValidator);
        $inputProcessor = new RequestParamsProcessor($parametersBinder, $performedMaintenanceDTOValidator);
        $motorcycleRepository = new DoctrineMotorcycleRepository($this->em);
        $performedOilChangeRepository = new DoctrinePerformedMaintenanceRepository($this->em, PerformedOilChange::class);
        
        $validator = new Validator($this->sfValidator);
        $performedOilChangePatcher = new PerformedMaintenancePatcher(
            $motorcycleRepository,
            $performedOilChangeRepository,
            $validator
        );
            
        return new PatchPerformedMaintenanceAction(
            $performedOilChangeRepository,
            $inputProcessor,
            $performedOilChangePatcher
        );
    }
}
