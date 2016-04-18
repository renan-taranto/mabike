<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Rtaranto\Application\EndpointAction\Factory\CgetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\OilChange\CgetOilChangeAction;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedOilChangeRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;

class CgetOilChangeActionFactory implements CgetActionFactoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * @param ParamFetcherInterface $paramFetcher
     * @return CgetOilChangeAction
     */
    public function createCgetAction(ParamFetcherInterface $paramFetcher)
    {
        $performedOilChangeRepository = new DoctrinePerformedOilChangeRepository($this->em);
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher);
        return new CgetOilChangeAction($performedOilChangeRepository, $queryParamsFetcher);
    }
}
