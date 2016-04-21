<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\EndpointAction\DeleteSubResourceAction;
use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;

class DeletePerformedOilChangeAction extends DeleteSubResourceAction
{
    private $performedOilChangeRepository;
    
    public function __construct(PerformedOilChangeRepositoryInterface $performedOilChangeRepository)
    {
        $this->performedOilChangeRepository = $performedOilChangeRepository;
    }
    
    protected function deleteSubResource($subResource)
    {
        $this->performedOilChangeRepository->delete($subResource);
    }

    protected function findSubResource($parentResourceId, $subResourceId)
    {
        return $this->performedOilChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }
}

