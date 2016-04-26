<?php
namespace Rtaranto\Application\Service\Maintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;

class OilChangerService implements OilChangerServiceInterface
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
     * @return PerformedOilChange
     */
    public function changeOil($motorcycle, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        /* @var $oilChange OilChange */
        $oilChange = $this->maintenanceRepository->findOneByMotorcycle($motorcycle);
        $kmsDriven = $performedMaintenanceDTO->getKmsDriven();
        $date = $performedMaintenanceDTO->getDate();
        
        $performedOilChange = $oilChange->changeOil($kmsDriven, $date);
        $this->validator->throwValidationFailedIfNotValid($performedOilChange);
        $this->maintenanceRepository->update($oilChange);
        return $performedOilChange;
    }
}
