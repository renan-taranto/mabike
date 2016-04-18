<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\EndpointAction\GetSubresourceActionInterface;
use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetOilChangeAction implements GetSubresourceActionInterface
{
    private $performedOilChangeRepository;
    
    public function __construct(PerformedOilChangeRepositoryInterface $performedOilChangeRepository)
    {
        $this->performedOilChangeRepository = $performedOilChangeRepository;
    }
    
    public function get($parentResourceId, $resourceId)
    {
        $performedOilChange = $this->performedOilChangeRepository
            ->findOneByMotorcycleAndId($parentResourceId, $resourceId);
        
        if (empty($performedOilChange)) {
            throw new NotFoundHttpException(
                sprintf('The Oil Change resource of id \'%s\' was not found.', $resourceId)
            );
        }
        
        return $performedOilChange;
    }
}
