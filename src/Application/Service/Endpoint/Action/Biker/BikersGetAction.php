<?php
namespace Application\Service\Endpoint\Action\Biker;

use Application\Service\Endpoint\Action\Biker\BikersGetActionInterface;
use Domain\Entity\Biker;
use Domain\Entity\Repository\BikerRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BikersGetAction implements BikersGetActionInterface
{
    private $bikerRepository;
    
    public function __construct(BikerRepository $bikerRepository)
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
