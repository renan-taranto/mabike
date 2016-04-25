<?php
namespace Rtaranto\Application\Service\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Maintenance;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;

abstract class MaintenancePerformerService implements MaintenancePerformerServiceInterface
{
    private $maintenanceRepository;
    private $validator;
    
    public function __construct(
        MaintenanceRepositoryInterface $maintenanceRepository,
        ValidatorInterface $validator
    ) {
        $this->maintenanceRepository = $maintenanceRepository;
        $this->validator = $validator;
    }
    
    public function performMaintenance($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        $maintenance = $this->getMaintenance($motorcycleId);
        
        $performedMaintenance = $this->createPerformedMaintenance($motorcycleId, $performedMaintenanceDTO);
        $maintenance->addPerformedMaintenance($performedMaintenance);
        $this->validator->throwValidationFailedIfNotValid($maintenance);

        $this->maintenanceRepository->update($maintenance);
        
        return $performedMaintenance;
    }
    
    /**
     * @param int $motorcycleId
     * @return Maintenance
     */
    protected function getMaintenance($motorcycleId)
    {
        return $this->maintenanceRepository->findOneByMotorcycle($motorcycleId);
    }
    
    abstract protected function createPerformedMaintenance(
        $motorcycleId,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    );
}
