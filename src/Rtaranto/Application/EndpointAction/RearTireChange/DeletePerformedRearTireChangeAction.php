<?php
namespace Rtaranto\Application\EndpointAction\RearTireChange;

use Rtaranto\Application\EndpointAction\DeleteSubResourceAction;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Domain\Entity\Repository\PerformedRearTireChangeRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\RearTireChangeRepositoryInterface;

class DeletePerformedRearTireChangeAction extends DeleteSubResourceAction
{
    private $rearTireChangeRepository;
    private $performedRearTireChangeRepository;
    
    public function __construct(
        RearTireChangeRepositoryInterface $rearTireChangeRepository,
        PerformedRearTireChangeRepositoryInterface $performedRearTireChangeRepository
    ) {
        $this->rearTireChangeRepository = $rearTireChangeRepository;
        $this->performedRearTireChangeRepository = $performedRearTireChangeRepository;
    }
    
    protected function deleteSubResource($parentResourceId, $subResource)
    {
        /* @var $rearTireChange RearTireChange */
        $rearTireChange = $this->rearTireChangeRepository->findOneByMotorcycle($parentResourceId);
        $rearTireChange->removePerformedMaintenance($subResource);
        $this->rearTireChangeRepository->update($rearTireChange);
    }

    protected function findSubResource($parentResourceId, $subResourceId)
    {
        return $this->performedRearTireChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }
}
