<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;
use Rtaranto\Domain\Entity\Repository\PerformedFrontTireChangeRepositoryInterface;

class PerformedFrontTireChangePatcher implements PerformedFrontTireChangePatcherInterface
{
    private $performedFrontTireChangeRepository;
    private $validator;
    
    public function __construct(
        PerformedFrontTireChangeRepositoryInterface $performedFrontTireChangeRepository,
        ValidatorInterface $validator
    ) {
        $this->performedFrontTireChangeRepository = $performedFrontTireChangeRepository;
        $this->validator = $validator;
    }
    
    public function patchPerformedFrontTireChange(
        PerformedFrontTireChange $performedFrontTireChange,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    ) {
        $performedFrontTireChange->setKmsDriven($performedMaintenanceDTO->getKmsDriven());
        $performedFrontTireChange->setDate($performedMaintenanceDTO->getDate());
        
        $this->validator->throwValidationFailedIfNotValid($performedFrontTireChange);
        
        return $this->performedFrontTireChangeRepository->update($performedFrontTireChange);
    }
}
