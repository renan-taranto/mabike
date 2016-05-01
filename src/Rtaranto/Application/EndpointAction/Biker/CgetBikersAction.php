<?php
namespace Rtaranto\Application\EndpointAction\Biker;

use Rtaranto\Application\EndpointAction\CgetActionInterface;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;

class CgetBikersAction implements CgetActionInterface
{
    private $bikerRepository;
    
    public function __construct(BikerRepositoryInterface $bikerRepository)
    {
        $this->bikerRepository = $bikerRepository;
    }
    
    public function cGet($filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        return $this->bikerRepository->getAll($filters, $orderBy, $limit, $offset);
    }
}
