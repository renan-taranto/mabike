<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\EndpointAction\GetSubResourceAction;
use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;

class GetPerformedOilChangeAction extends GetSubResourceAction
{
    private $performedOilChangeRepository;
    
    public function __construct(PerformedOilChangeRepositoryInterface $performedOilChangeRepository)
    {
        $this->performedOilChangeRepository = $performedOilChangeRepository;
    }
    
    protected function findSubResource($parentResourceId, $subResourceId)
    {
        return $this->performedOilChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $subResourceId);
    }
}