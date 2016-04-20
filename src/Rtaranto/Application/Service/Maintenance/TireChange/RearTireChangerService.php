<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;

class RearTireChangerService implements RearTireChangerServiceInterface
{
    private $subResourceRepository;
    private $validator;
    
    /**
     * @param SubResourceRepositoryInterface $subResourceRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        SubResourceRepositoryInterface $subResourceRepository,
        ValidatorInterface $validator
    ) {
        $this->subResourceRepository = $subResourceRepository;
        $this->validator = $validator;
    }
    
    /**
     * @param int $motorcycleId
     * @param PerformedMaintenanceDTO $performedMaintenanceDTO
     * @return type
     */
    public function changeRearTire($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        /* @var $rearTireChange RearTireChange */
        $rearTireChange = $this->subResourceRepository->findOneByParentResource($motorcycleId);

        $rearTireChangePerformed = $rearTireChange->changeRearTire(
            $performedMaintenanceDTO->getKmsDriven(),
            $performedMaintenanceDTO->getDate()
        );
        $this->validator->throwValidationFailedIfNotValid($rearTireChange);
        
        $this->subResourceRepository->update($rearTireChange);
        return $rearTireChangePerformed;
    }
}
