<?php
namespace Rtaranto\Application\Service\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\PerformedMaintenance;
use Rtaranto\Domain\Entity\Repository\PerformedMaintenanceRepositoryInterface;

class PerformedMaintenancePatcher implements PerformedMaintenancePatcherInterface
{
    private $performedMaintenanceRepository;
    private $validator;
    
    public function __construct(
        PerformedMaintenanceRepositoryInterface $performedMaintenanceRepository,
        ValidatorInterface $validator
    ) {
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
        return $this->performedMaintenanceRepository->update($performedMaintenance);
    }
}
