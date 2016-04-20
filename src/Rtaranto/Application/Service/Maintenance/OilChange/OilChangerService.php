<?php
namespace Rtaranto\Application\Service\Maintenance\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;

class OilChangerService implements OilChangerServiceInterface
{
    private $validator;
    private $subResourceRepository;
    
    public function __construct(
        ValidatorInterface $validator,
        SubResourceRepositoryInterface $subResourceRepository
    ) {
        $this->validator = $validator;
        $this->subResourceRepository = $subResourceRepository;
    }
    
    public function changeOil($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        $oilChange = $this->subResourceRepository->findOneByParentResource($motorcycleId);
        
        $oilChangePerformed = $oilChange->changeOil(
            $performedMaintenanceDTO->getKmsDriven(),
            $performedMaintenanceDTO->getDate()
        );
        $this->validator->throwValidationFailedIfNotValid($oilChange);

        $this->subResourceRepository->update($oilChange);
        
        return $oilChangePerformed;
    }    
}
