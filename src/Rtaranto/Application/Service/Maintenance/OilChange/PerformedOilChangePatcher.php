<?php
namespace Rtaranto\Application\Service\Maintenance\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;

class PerformedOilChangePatcher implements PerformedOilChangePatcherInterface
{
    private $subResourceRepository;
    private $validator;
    
    public function __construct(
        SubResourceRepositoryInterface $subResourceRepository,
        ValidatorInterface $validator
    ) {
        $this->subResourceRepository = $subResourceRepository;
        $this->validator = $validator;
    }
    
    public function patchPerformedOilChange(
        PerformedOilChange $performedOilChange,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    ) {
        $performedOilChange->setKmsDriven($performedMaintenanceDTO->getKmsDriven());
        $performedOilChange->setDate($performedMaintenanceDTO->getDate());
        
        $this->validator->throwValidationFailedIfNotValid($performedOilChange);
        
        return $this->subResourceRepository->update($performedOilChange);
    }
}
