<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;
use Rtaranto\Domain\Entity\Repository\FrontTireChangeRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\PerformedFrontTireChangeRepositoryInterface;

class PerformedFrontTireChangePatcher implements PerformedFrontTireChangePatcherInterface
{
    private $frontTireChangeRepository;
    private $performedFrontTireChangeRepository;
    private $validator;
    
    public function __construct(
        FrontTireChangeRepositoryInterface $frontTireChangeRepository,
        PerformedFrontTireChangeRepositoryInterface $performedFrontTireChangeRepository,
        ValidatorInterface $validator
    ) {
        $this->frontTireChangeRepository = $frontTireChangeRepository;
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
        $this->notifyWarningObserver($performedFrontTireChange);
        
        return $this->performedFrontTireChangeRepository->update($performedFrontTireChange);
    }
    
    private function notifyWarningObserver(PerformedFrontTireChange $performedFrontTireChange)
    {
        /* @var $frontTireChange FrontTireChange */
        $frontTireChange = $this->frontTireChangeRepository->findOneByPerformedMaintenance($performedFrontTireChange);
        $frontTireChange->notifyMotorcyleMaintenanceWarningObservers();
    }
}
