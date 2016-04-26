<?php
namespace Rtaranto\Application\Service\Maintenance;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;
use Rtaranto\Domain\Entity\Repository\MaintenanceRepositoryInterface;

class FrontTireChangerService implements FrontTireChangerServiceInterface
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
     * @param Motorcycle|id $motorcycle
     * @param PerformedMaintenanceDTO $performedMaintenanceDTO
     * @return PerformedFrontTireChange
     */
    public function changeFrontTire($motorcycle, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        /* @var $frontTireChange FrontTireChange */
        $frontTireChange = $this->maintenanceRepository->findOneByMotorcycle($motorcycle);
        $kmsDriven = $performedMaintenanceDTO->getKmsDriven();
        $date = $performedMaintenanceDTO->getDate();
        
        $performedFrontTireChange = $frontTireChange->changeFrontTire($kmsDriven, $date);
        $this->validator->throwValidationFailedIfNotValid($performedFrontTireChange);
        $this->maintenanceRepository->update($frontTireChange);
        return $performedFrontTireChange;
    }
}
