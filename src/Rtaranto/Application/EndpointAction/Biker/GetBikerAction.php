<?php
namespace Rtaranto\Application\EndpointAction\Biker;

use Rtaranto\Application\EndpointAction\GetActionInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetBikerAction implements GetActionInterface
{
    private $bikerRepository;
    
    public function __construct(BikerRepositoryInterface $bikerRepository)
    {
        $this->bikerRepository = $bikerRepository;
    }
    
    /**
     * @param integer $id
     * @return Biker
     */
    public function get($id)
    {
        $biker = $this->bikerRepository->get($id);
        
        if (empty($biker)) {
           throw new NotFoundHttpException(
                sprintf('The Biker resource of id \'%s\' was not found.', $id));
        }
        
        return $biker;
    }
}
