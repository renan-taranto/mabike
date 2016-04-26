<?php
namespace Rtaranto\Application\Service\Maintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;

class OilChangerService implements OilChangerServiceInterface
{
    private $motorcycleRepository;
    private $maintenanceRepository;
    private $validator;
    
    public function __construct(
        MotorcycleRepositoryInterface $motorcycleRepository,
        MaintenanceRepositoryInterface $maintenanceRepository,
        ValidatorInterface $validator
    ) {
        $this->motorcycleRepository = $motorcycleRepository;
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
        $this->notifyWarningsObservers($motorcycle);
        return $performedOilChange;
    }
    
    private function notifyWarningsObservers($motorcycle)
    {
        /* @var $motorcycleFromRepository Motorcycle */
        $motorcycleFromRepository = $this->motorcycleRepository->get($motorcycle);
        $motorcycleFromRepository->notifyMaintenanceWarningObservers();
        $this->motorcycleRepository->update($motorcycleFromRepository);
    }
}
