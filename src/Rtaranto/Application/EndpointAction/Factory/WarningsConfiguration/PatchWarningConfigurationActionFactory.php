<?php
namespace Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\EndpointAction\WarningsConfiguration\PatchWarnigsConfigurationAction;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceWarninObserverRepository;
use Rtaranto\Presentation\Form\WarningsConfiguration\MaintenanceWarningConfigurationDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class PatchWarningConfigurationActionFactory implements PatchWarningConfigurationActionFactoryInterface
{
    protected $em;
    protected $sfValidator;
    protected $formFactory;
    
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
        
        $validator = new Validator($this->sfValidator);
        $maintenanceRepository = new DoctrineMaintenanceRepository($this->em, $maintenanceClassName);
        $maintenanceWarningConfigurationPatcher = $this->createMaintenanceWarningConfigurationsPatcher(
            $maintenanceWarningObserverRepository,
            $maintenanceRepository);
        
        $maintenanceWarningConfigurationsDTOFactory = $this->createMaintenanceWarningConfigurationsDTOFactory(
            $maintenanceWarningObserverRepository,
            $maintenanceRepository
        );
        
        $parametersBinder = new ParametersBinder($this->formFactory, MaintenanceWarningConfigurationDTOType::class);
        $inputProcessor = new RequestParamsProcessor($parametersBinder, $validator);
        return new PatchWarnigsConfigurationAction(
            $maintenanceWarningConfigurationsDTOFactory,
            $maintenanceWarningConfigurationPatcher,
            $inputProcessor
        );
    }
    
    abstract protected function createMaintenanceWarningConfigurationsDTOFactory(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        MaintenanceRepositoryInterface $maintenanceRepository
    );
    
    abstract protected function createMaintenanceWarningConfigurationsPatcher(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        MaintenanceRepositoryInterface $maintenanceRepository
    );
}
