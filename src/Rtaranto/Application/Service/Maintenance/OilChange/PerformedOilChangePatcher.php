<?php
namespace Rtaranto\Application\Service\Maintenance\OilChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;

class PerformedOilChangePatcher implements PerformedOilChangePatcherInterface
{
    private $performedOilChangeRepository;
    private $validator;
    
    public function __construct(
        PerformedOilChangeRepositoryInterface $performedOilChangeRepository,
        ValidatorInterface $validator
    ) {
        $this->performedOilChangeRepository = $performedOilChangeRepository;
        $this->validator = $validator;
    }
    
    public function patchPerformedOilChange(
        PerformedOilChange $performedOilChange,
        PerformedMaintenanceDTO $performedMaintenanceDTO
    ) {
        $performedOilChange->setKmsDriven($performedMaintenanceDTO->getKmsDriven());
        $performedOilChange->setDate($performedMaintenanceDTO->getDate());
        
        $this->validator->throwValidationFailedIfNotValid($performedOilChange);
        
        return $this->performedOilChangeRepository->update($performedOilChange);
    }
}