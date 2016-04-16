<?php
namespace Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteMotorcycleAction
{
    private $motorcycleRepository;
    
    public function __construct(MotorcycleRepositoryInterface $motorcycleRepository)
    {
        $this->motorcycleRepository = $motorcycleRepository;
    }
    
    public function delete($id)
    {
        $this->findOrThrowNotFound($id);
        $this->motorcycleRepository->delete($id);
    }
    
    private function findOrThrowNotFound($id)
    {
        $motorcycle = $this->motorcycleRepository->get($id);
        
        if (empty($motorcycle)) {
            throw new NotFoundHttpException(
                sprintf('The Motorcycle resource of id \'%s\' was not found.', $id)
            );
        }
        
        return $motorcycle;
    }
}
