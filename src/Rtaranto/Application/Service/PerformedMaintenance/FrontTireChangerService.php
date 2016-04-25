<?php
namespace Rtaranto\Application\Service\PerformedMaintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;

class FrontTireChangerService extends MaintenancePerformerService
{
    private $motorcycleRepository;
    
    public function __construct(
        MaintenanceRepositoryInterface $maintenanceRepository,
        ValidatorInterface $validator,
        MotorcycleRepositoryInterface $motorcycleRepository
    ) {
        parent::__construct($maintenanceRepository, $validator);
        $this->motorcycleRepository = $motorcycleRepository;
    }
    
    protected function createPerformedMaintenance($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        $motorcycle = $this->motorcycleRepository->get($motorcycleId);
        $kmsDriven = $performedMaintenanceDTO->getKmsDriven();
        $date = $performedMaintenanceDTO->getDate();
        return new PerformedFrontTireChange($motorcycle, $kmsDriven, $date);
    }

}
