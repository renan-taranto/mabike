<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\EndpointAction\GetSubresourceActionInterface;
use Rtaranto\Domain\Entity\Repository\MaintenancePerformerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetOilChangeAction implements GetSubresourceActionInterface
{
    private $maintenancePerformerRepository;
    
    public function __construct(MaintenancePerformerRepositoryInterface $maintenancePerformerRepository)
    {
        $this->maintenancePerformerRepository = $maintenancePerformerRepository;
    }
    
    public function get($parentResourceId, $resourceId)
    {
        $performedOilChange = $this->maintenancePerformerRepository
            ->getPerformedOilChangeByMotorcycleAndId($parentResourceId, $resourceId);
        
        if (empty($performedOilChange)) {
            throw new NotFoundHttpException(
                sprintf('The Oil Change resource of id \'%s\' was not found.', $resourceId)
            );
        }
        
        return $performedOilChange;
    }
}
