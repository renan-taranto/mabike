<?php
namespace Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteMotorcycleAction
{
    private $biker;
    private $motorcycleRepository;
    
    public function __construct(Biker $biker, MotorcycleRepositoryInterface $motorcycleRepository)
    {
        $this->biker = $biker;
        $this->motorcycleRepository = $motorcycleRepository;
    }
    
    public function delete($id)
    {
        $this->findOrThrowNotFound($id);
        $this->motorcycleRepository->delete($id);
    }
    
    private function findOrThrowNotFound($id)
    {
        $motorcycle = $this->motorcycleRepository->findOneByBikerAndId($this->biker, $id);
        
        if (empty($motorcycle)) {
            throw new NotFoundHttpException(
                sprintf('The Motorcycle resource of id \'%s\' was not found.', $id)
            );
        }
        
        return $motorcycle;
    }
}
