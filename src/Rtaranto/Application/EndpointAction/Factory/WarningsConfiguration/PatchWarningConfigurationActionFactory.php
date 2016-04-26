<?php
namespace Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\EndpointAction\WarningsConfiguration\PatchWarnigsConfigurationAction;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Maintenance\WarningsConfiguration\MaintenanceWarningConfigurationPatcher;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceWarninObserverRepository;
use Rtaranto\Presentation\Form\WarningsConfiguration\MaintenanceWarningConfigurationDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatchWarningConfigurationActionFactory implements PatchWarningConfigurationActionFactoryInterface
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
    
    /**
     * @return PatchWarnigsConfigurationAction
     */
    public function createPatchAction($maintenanceClassName, $maintenanceWarningObserverClassName)
    {
        $maintenanceWarningObserverRepository = new DoctrineMaintenanceWarninObserverRepository(
            $this->em,
            $maintenanceWarningObserverClassName
        );
        
        $oilChangeRepository = new DoctrineMaintenanceRepository($this->em, $maintenanceClassName);
        $validator = new Validator($this->sfValidator);
        $oilChangeWarningPatcher = new MaintenanceWarningConfigurationPatcher(
            $maintenanceWarningObserverRepository,
            $oilChangeRepository,
            $validator
        );
        
        $parametersBinder = new ParametersBinder($this->formFactory, MaintenanceWarningConfigurationDTOType::class);
        $inputProcessor = new RequestParamsProcessor($parametersBinder, $validator);
        return new PatchWarnigsConfigurationAction(
            $maintenanceWarningObserverRepository,
            $oilChangeRepository,
            $oilChangeWarningPatcher,
            $inputProcessor
        );
    }
}
