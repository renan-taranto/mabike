<?php
namespace Rtaranto\Application\EndpointAction\RearTireChange;

use Rtaranto\Application\EndpointAction\DeleteSubResourceAction;
use Rtaranto\Domain\Entity\Repository\PerformedRearTireChangeRepositoryInterface;

class DeletePerformedRearTireChangeAction extends DeleteSubResourceAction
{
    private $performedRearTireChangeRepository;
    
    public function __construct(PerformedRearTireChangeRepositoryInterface $performedRearTireChangeRepository)
    {
        $this->performedRearTireChangeRepository = $performedRearTireChangeRepository;
    }
    
    protected function deleteSubResource($subResource)
    {
        $this->performedRearTireChangeRepository->delete($subResource);
    }

    protected function findSubResource($parentResourceId, $subResourceId)
    {
        return $this->performedRearTireChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }
}
