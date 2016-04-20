<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;
use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;

class PerformedFrontTireChangePatcher implements PerformedFrontTireChangePatcherInterface
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
    
    public function patchPerformedFrontTireChange(
        PerformedFrontTireChange $performedFrontTireChange,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    ) {
        $performedFrontTireChange->setKmsDriven($performedMaintenanceDTO->getKmsDriven());
        $performedFrontTireChange->setDate($performedMaintenanceDTO->getDate());
        
        $this->validator->throwValidationFailedIfNotValid($performedFrontTireChange);
        
        return $this->subResourceRepository->update($performedFrontTireChange);
    }
}
