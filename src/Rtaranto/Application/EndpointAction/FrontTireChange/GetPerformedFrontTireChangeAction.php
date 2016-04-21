<?php
namespace Rtaranto\Application\EndpointAction\FrontTireChange;

use Rtaranto\Application\EndpointAction\GetSubResourceAction;
use Rtaranto\Domain\Entity\Repository\PerformedFrontTireChangeRepositoryInterface;

class GetPerformedFrontTireChangeAction extends GetSubResourceAction
{
    private $performedFrontTireChangeRepository;
    
    public function __construct(PerformedFrontTireChangeRepositoryInterface $performedFrontTireChangeRepository)
    {
        $this->performedFrontTireChangeRepository = $performedFrontTireChangeRepository;
    }
    
    protected function findSubResource($parentResourceId, $subResourceId)
    {
        return $this->performedFrontTireChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }
}
