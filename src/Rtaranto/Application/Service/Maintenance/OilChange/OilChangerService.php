<?php
namespace Rtaranto\Application\Service\Maintenance\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Repository\OilChangeRepositoryInterface;

class OilChangerService implements OilChangerServiceInterface
{
    private $validator;
    private $oilChangeRepository;
    
    public function __construct(
        ValidatorInterface $validator,
        OilChangeRepositoryInterface $oilChangeRepository
    ) {
        $this->validator = $validator;
        $this->oilChangeRepository = $oilChangeRepository;
    }
    
    public function changeOil($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        $oilChange = $this->oilChangeRepository->findOneByMotorcycle($motorcycleId);
        
        $oilChangePerformed = $oilChange->changeOil(
            $performedMaintenanceDTO->getKmsDriven(),
            $performedMaintenanceDTO->getDate()
        );
        $this->validator->throwValidationFailedIfNotValid($oilChange);

        $this->oilChangeRepository->update($oilChange);
        
        return $oilChangePerformed;
    }    
}
