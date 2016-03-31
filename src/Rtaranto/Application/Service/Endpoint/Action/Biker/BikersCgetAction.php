<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;

class BikersCgetAction implements BikersCgetActionInterface
{
    private $bikerRepository;
    
    public function __construct(BikerRepositoryInterface $bikerRepository)
    {
        $this->bikerRepository = $bikerRepository;
    }
    
    public function get()
    {
        return $this->bikerRepository->getAll();
    }

}
