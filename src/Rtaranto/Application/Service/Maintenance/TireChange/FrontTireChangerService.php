<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\Repository\FrontTireChangeRepositoryInterface;

class FrontTireChangerService implements FrontTireChangerServiceInterface
{
    private $frontTireChangeRepository;
    private $validator;
    
    /**
     * @param FrontTireChangeRepositoryInterface $frontTireChangeRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        FrontTireChangeRepositoryInterface $frontTireChangeRepository,
        ValidatorInterface $validator
    ) {
        $this->frontTireChangeRepository = $frontTireChangeRepository;
        $this->validator = $validator;
    }

    public function changeFrontTire($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        /* @var $frontTireChange FrontTireChange */
        $frontTireChange = $this->frontTireChangeRepository->findOneByMotorcycle($motorcycleId);

        $performedFrontTireChange = $frontTireChange->changeFrontTire(
            $performedMaintenanceDTO->getKmsDriven(),
            $performedMaintenanceDTO->getDate()
        );
        $this->validator->throwValidationFailedIfNotValid($frontTireChange);
        
        $this->frontTireChangeRepository->update($frontTireChange);
        return $performedFrontTireChange;
    }
}
