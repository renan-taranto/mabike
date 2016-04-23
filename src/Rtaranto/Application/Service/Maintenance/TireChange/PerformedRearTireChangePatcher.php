<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Maintenance\TireChange\PerformedRearTireChangePatcherInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Domain\Entity\Repository\PerformedRearTireChangeRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\RearTireChangeRepositoryInterface;

class PerformedRearTireChangePatcher implements PerformedRearTireChangePatcherInterface
{
    private $rearTireChangeRepository;
    private $performedRearTireChangeRepository;
    private $validator;
    
    public function __construct(
        RearTireChangeRepositoryInterface $rearTireChangeRepository,
        PerformedRearTireChangeRepositoryInterface $performedRearTireChangeRepository,
        ValidatorInterface $validator
    ) {
        $this->rearTireChangeRepository = $rearTireChangeRepository;
        $this->performedRearTireChangeRepository = $performedRearTireChangeRepository;
        $this->validator = $validator;
    }
    
    public function patchPerformedRearTireChange(
        PerformedRearTireChange $performedRearTireChange,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    ) {
        $performedRearTireChange->setKmsDriven($performedMaintenanceDTO->getKmsDriven());
        $performedRearTireChange->setDate($performedMaintenanceDTO->getDate());
        
        $this->validator->throwValidationFailedIfNotValid($performedRearTireChange);
        $this->notifyWarningObserver($performedRearTireChange);
        return $this->performedRearTireChangeRepository->update($performedRearTireChange);
    }
    
    private function notifyWarningObserver(PerformedRearTireChange $performedRearTireChange)
    {
        /* @var $rearTireChange RearTireChange */
        $rearTireChange = $this->rearTireChangeRepository->findOneByPerformedMaintenance($performedRearTireChange);
        $rearTireChange->notifyMotorcyleMaintenanceWarningObservers();
    }
}

