<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Domain\Entity\Repository\RearTireChangeRepositoryInterface;

class RearTireChangerService implements RearTireChangerServiceInterface
{
    private $rearTireChangeRepository;
    private $validator;
    
    /**
     * @param RearTireChangeRepositoryInterface $rearTireChangeRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        RearTireChangeRepositoryInterface $rearTireChangeRepository,
        ValidatorInterface $validator
    ) {
        $this->rearTireChangeRepository = $rearTireChangeRepository;
        $this->validator = $validator;
    }
    
    /**
     * @param int $motorcycleId
     * @param PerformedMaintenanceDTO $performedMaintenanceDTO
     * @return PerformedRearTireChange
     */
    public function changeRearTire($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        /* @var $rearTireChange RearTireChange */
        $rearTireChange = $this->rearTireChangeRepository->findOneByMotorcycle($motorcycleId);

        $performedRearTireCHange = $rearTireChange->changeRearTire(
            $performedMaintenanceDTO->getKmsDriven(),
            $performedMaintenanceDTO->getDate()
        );
        $this->validator->throwValidationFailedIfNotValid($rearTireChange);
        
        $this->rearTireChangeRepository->update($rearTireChange);
        return $performedRearTireCHange;
    }
}
