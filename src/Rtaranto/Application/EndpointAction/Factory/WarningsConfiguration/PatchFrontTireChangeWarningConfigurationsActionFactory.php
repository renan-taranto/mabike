<?php
namespace Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\Dto\WarningsConfiguration\FrontTireChangeWarningConfigurationsDTOFactory;
use Rtaranto\Application\Service\Maintenance\WarningsConfiguration\FrontTireChangeWarningConfigurationPatcher;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MaintenanceWarningObserverRepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatchFrontTireChangeWarningConfigurationsActionFactory extends PatchWarningConfigurationActionFactory
{
    public function __construct(
        FormFactoryInterface $formFactory,
        ValidatorInterface $sfValidator,
        EntityManagerInterface $em
    ) {
        parent::__construct($formFactory, $sfValidator, $em);
    }
    
    protected function createMaintenanceWarningConfigurationsDTOFactory(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        MaintenanceRepositoryInterface $maintenanceRepository
    ) {
        return new FrontTireChangeWarningConfigurationsDTOFactory(
            $maintenanceWarningObserverRepository,
            $maintenanceRepository
        );
    }

    protected function createMaintenanceWarningConfigurationsPatcher(
        MaintenanceWarningObserverRepositoryInterface $maintenanceWarningObserverRepository,
        MaintenanceRepositoryInterface $maintenanceRepository
    ) {
        $validator = new Validator($this->sfValidator);
        return new FrontTireChangeWarningConfigurationPatcher(
            $maintenanceWarningObserverRepository,
            $maintenanceRepository,
            $validator
        );
    }
}
