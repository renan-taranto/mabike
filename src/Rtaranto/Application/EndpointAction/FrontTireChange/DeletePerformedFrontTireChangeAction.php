<?php
namespace Rtaranto\Application\EndpointAction\FrontTireChange;

use Rtaranto\Application\EndpointAction\DeleteSubResourceAction;
use Rtaranto\Domain\Entity\Repository\PerformedFrontTireChangeRepositoryInterface;

class DeletePerformedFrontTireChangeAction extends DeleteSubResourceAction
{
    private $performedFrontTireChangeRepository;
    
    public function __construct(PerformedFrontTireChangeRepositoryInterface $performedFrontTireChangeRepository)
    {
        $this->performedFrontTireChangeRepository = $performedFrontTireChangeRepository;
    }
    
    protected function deleteSubResource($subResource)
    {
        $this->performedFrontTireChangeRepository->delete($subResource);
    }

    protected function findSubResource($parentResourceId, $subResourceId)
    {
        return $this->performedFrontTireChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }
}
