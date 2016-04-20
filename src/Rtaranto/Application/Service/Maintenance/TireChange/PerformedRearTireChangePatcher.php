<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Maintenance\TireChange\PerformedRearTireChangePatcherInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;

class PerformedRearTireChangePatcher implements PerformedRearTireChangePatcherInterface
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
    
    public function patchPerformedRearTireChange(
        PerformedRearTireChange $performedRearTireChange,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    ) {
        $performedRearTireChange->setKmsDriven($performedMaintenanceDTO->getKmsDriven());
        $performedRearTireChange->setDate($performedMaintenanceDTO->getDate());
        
        $this->validator->throwValidationFailedIfNotValid($performedRearTireChange);
        
        return $this->subResourceRepository->update($performedRearTireChange);
    }
}
