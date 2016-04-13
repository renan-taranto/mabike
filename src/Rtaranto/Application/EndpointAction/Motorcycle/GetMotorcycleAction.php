<?php
namespace Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\EndpointAction\GetActionInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetMotorcycleAction implements GetActionInterface
{
    /**
     * @var MotorcycleRepositoryInterface
     */
    private $motorcycleRepository;
    
    /**
     * @var Biker
     */
    private $biker;
    
    public function __construct(MotorcycleRepositoryInterface $motorcycleRepository, Biker $biker)
    {
        $this->motorcycleRepository = $motorcycleRepository;
        $this->biker = $biker;
    }
    
    /**
     * @param integer $id
     * @return Motorcycle
     * @throws NotFoundHttpException
     */
    public function get($id)
    {
        return $this->findOrThrowNotFound($id);
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
