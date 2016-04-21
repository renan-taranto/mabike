<?php
namespace Rtaranto\Application\EndpointAction\RearTireChange;

use Rtaranto\Application\EndpointAction\GetSubResourceAction;
use Rtaranto\Domain\Entity\Repository\PerformedRearTireChangeRepositoryInterface;

class GetPerformedRearTireChangeAction extends GetSubResourceAction
{
    private $performedRearTireChangeRepository;
    
    public function __construct(PerformedRearTireChangeRepositoryInterface $performedRearTireChangeRepository)
    {
        $this->performedRearTireChangeRepository = $performedRearTireChangeRepository;
    }
    
    protected function findSubResource($parentResourceId, $subResourceId)
    {
        return $this->performedRearTireChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }
}
