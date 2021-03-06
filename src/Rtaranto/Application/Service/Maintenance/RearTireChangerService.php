<?php
namespace Rtaranto\Application\Service\Maintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;

class RearTireChangerService implements RearTireChangerServiceInterface
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
    
    /**
     * @param Motorcycle|int $motorcycle
     * @param PerformedMaintenanceDTO $performedMaintenanceDTO
     * @return PerformedRearTireChange
     */
    public function changeRearTire($motorcycle, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        /* @var $rearTireChange RearTireChange */
        $rearTireChange = $this->maintenanceRepository->findOneByMotorcycle($motorcycle);
        $kmsDriven = $performedMaintenanceDTO->getKmsDriven();
        $date = $performedMaintenanceDTO->getDate();
        
        $performedRearTireChange = $rearTireChange->changeRearTire($kmsDriven, $date);
        $this->validator->throwValidationFailedIfNotValid($performedRearTireChange);
        $this->maintenanceRepository->update($rearTireChange);
        return $performedRearTireChange;
    }
}
