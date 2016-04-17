<?php
namespace Rtaranto\Application\Service\Maintenance\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\MaintenancePerformer;
use Rtaranto\Domain\Entity\Repository\MaintenancePerformerRepositoryInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class OilChangePoster implements OilChangePosterInterface
{
    private $validator;
    private $maintenancePerformerRepository;
    
    public function __construct(
        ValidatorInterface $validator,
        MaintenancePerformerRepositoryInterface $maintenancePerformerRepository
    ) {
        $this->validator = $validator;
        $this->maintenancePerformerRepository = $maintenancePerformerRepository;
    }
    
    public function postOilChange($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        $maintenacePerformer = $this->getMaintenancePerformer($motorcycleId);
        $oilChangePerformed = $maintenacePerformer->changeOil(
            $performedMaintenanceDTO->getKmsDriven(),
            $performedMaintenanceDTO->getDate()
        );
        $this->validator->throwValidationFailedIfNotValid($maintenacePerformer);
        
        $this->maintenancePerformerRepository->update($maintenacePerformer);
        
        return $oilChangePerformed;
    }
    
    /**
     * @param int $motorcycleId
     * @return MaintenancePerformer
     * @throws Exception
     */
    private function getMaintenancePerformer($motorcycleId)
    {
        $maintenancePerformer = $this->maintenancePerformerRepository
            ->findByMotorcycle($motorcycleId);
        if (empty($maintenancePerformer)) {
            throw new Exception('OilChangePerformer not found.');
        }
        
        return $maintenancePerformer;
    }
}
