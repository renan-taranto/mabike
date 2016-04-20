<?php
namespace Rtaranto\Application\Service\Maintenance\TireChange;

use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;

class FrontTireChangerService implements FrontTireChangerServiceInterface
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

    public function changeFrontTire($motorcycleId, PerformedMaintenanceDTO $performedMaintenanceDTO)
    {
        /* @var $frontTireChange FrontTireChange */
        $frontTireChange = $this->subResourceRepository->findOneByParentResource($motorcycleId);

        $performedFrontTireChange = $frontTireChange->changeFrontTire(
            $performedMaintenanceDTO->getKmsDriven(),
            $performedMaintenanceDTO->getDate()
        );
        $this->validator->throwValidationFailedIfNotValid($frontTireChange);
        
        $this->subResourceRepository->update($frontTireChange);
        return $performedFrontTireChange;
        
    }
}
