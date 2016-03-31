<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersGetActionInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BikersGetAction implements BikersGetActionInterface
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
