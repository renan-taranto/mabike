<?php
namespace Application\Service\Endpoint\Action\Biker;

use Domain\Entity\Repository\BikerRepository;

class BikersCgetAction implements BikersCgetActionInterface
{
    private $bikerRepository;
    
    public function __construct(BikerRepository $bikerRepository)
    {
        $this->bikerRepository = $bikerRepository;
    }
    
    public function get()
    {
        return $this->bikerRepository->getAll();
    }

}
