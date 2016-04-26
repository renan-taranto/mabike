<?php
namespace Rtaranto\Application\Service\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\PerformedMaintenance;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;

class PerformedMaintenancePatcher implements PerformedMaintenancePatcherInterface
{
    private $motorcycleRepository;
    private $performedMaintenanceRepository;
    private $validator;
    
    public function __construct(
        MotorcycleRepositoryInterface $motorcycleRepository,
        PerformedMaintenanceRepositoryInterface $performedMaintenanceRepository,
        ValidatorInterface $validator
    ) {
        $this->motorcycleRepository = $motorcycleRepository;
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
        $this->notifyWarningObserver($performedMaintenanceDTO->getMotorcycleId());
        return $this->performedMaintenanceRepository->update($performedMaintenance);
    }
    
    private function notifyWarningObserver($motorcycleId)
    {
        /* @var $motorcycle Motorcycle */
        $motorcycle = $this->motorcycleRepository->get($motorcycleId);
        $motorcycle->notifyMaintenanceWarningObservers();
        $this->motorcycleRepository->update($motorcycle);
    }

}
