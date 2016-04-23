<?php
namespace Rtaranto\Application\EndpointAction\FrontTireChange;

use Rtaranto\Application\EndpointAction\DeleteSubResourceAction;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\Repository\FrontTireChangeRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\PerformedFrontTireChangeRepositoryInterface;

class DeletePerformedFrontTireChangeAction extends DeleteSubResourceAction
{
    private $performedFrontTireChangeRepository;
    private $frontTireChangeRepository;
    
    public function __construct(
        FrontTireChangeRepositoryInterface $frontTireChangeRepository,
        PerformedFrontTireChangeRepositoryInterface $performedFrontTireChangeRepository
    ) {
        $this->frontTireChangeRepository = $frontTireChangeRepository;
        $this->performedFrontTireChangeRepository = $performedFrontTireChangeRepository;
    }
    
    protected function deleteSubResource($parentResourceId, $subResource)
    {
        /* @var $frontTireChange FrontTireChange */
        $frontTireChange = $this->frontTireChangeRepository->findOneByMotorcycle($parentResourceId);
        $frontTireChange->removePerformedMaintenance($subResource);
        $this->frontTireChangeRepository->update($frontTireChange);
    }

    protected function findSubResource($parentResourceId, $subResourceId)
    {
        return $this->performedFrontTireChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }
}
