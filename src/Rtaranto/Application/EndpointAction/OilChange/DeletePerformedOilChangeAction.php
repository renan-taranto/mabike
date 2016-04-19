<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Rtaranto\Application\EndpointAction\DeleteSubResourceActionInterface;
use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeletePerformedOilChangeAction implements DeleteSubResourceActionInterface
{
    private $performedOilChangeRepository;
    
    public function __construct(PerformedOilChangeRepositoryInterface $performedOilChangeRepository)
    {
        $this->performedOilChangeRepository = $performedOilChangeRepository;
    }
    
    public function delete($parentResourceId, $subResourceId)
    {
        $performedOilChange = $this->findOrThrowNotFound($parentResourceId, $subResourceId);
        $this->performedOilChangeRepository->delete($performedOilChange);
    }
    
    public function findOrThrowNotFound($motorcycleId, $performedOilChangeId)
    {
        $performedOilChange = $this->performedOilChangeRepository
            ->findOneByMotorcycleAndId($motorcycleId, $performedOilChangeId);
        
        if (empty($performedOilChange)) {
            throw new NotFoundHttpException(
                sprintf('The Oil Change resource of id \'%s\' was not found.', $performedOilChangeId)
            );
        }
        
        return $performedOilChange;
    }
}
