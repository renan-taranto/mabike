<?php
namespace Rtaranto\Application\Service\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\PerformedMaintenance;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;

class PerformedMaintenancePatcher implements PerformedMaintenancePatcherInterface
{
    private $maintenanceRepository;
    private $performedMaintenanceRepository;
    private $validator;
    
    public function __construct(
        MaintenanceRepositoryInterface $maintenanceRepository,
        PerformedMaintenanceRepositoryInterface $performedMaintenanceRepository,
        ValidatorInterface $validator
    ) {
        $this->maintenanceRepository = $maintenanceRepository;
        $this->performedMaintenanceRepository = $performedMaintenanceRepository;
        $this->validator = $validator;
    }
    
    public function patchPerformedMaintenance(
        PerformedMaintenance $performedMaintenance,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    ) {
        $performedMaintenance->setKmsDriven($performedMaintenanceDTO->getKmsDriven());
        $performedMaintenance->setDate($performedMaintenanceDTO->getDate());
        
        $this->validator->throwValidationFailedIfNotValid($performedMaintenance);
        $this->notifyWarningObserver($performedMaintenance);
        return $this->performedMaintenanceRepository->update($performedMaintenance);
    }
    
    private function notifyWarningObserver(PerformedMaintenance $performedMaintenance)
    {
        /* @var $maintenance Maintenance */
        $maintenance = $this->maintenanceRepository->findOneByPerformedMaintenance($performedMaintenance);
        $maintenance->notifyMotorcyleMaintenanceWarningObservers();
    }

}
