<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Rtaranto\Application\EndpointAction\Factory\CgetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\OilChange\CgetPerformedOilChangeAction;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedOilChangeRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;

class CgetPerformedOilChangeActionFactory implements CgetActionFactoryInterface
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
     * @return CgetPerformedOilChangeAction
     */
    public function createCgetAction(ParamFetcherInterface $paramFetcher)
    {
        $performedOilChangeRepository = new DoctrinePerformedOilChangeRepository($this->em);
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher);
        return new CgetPerformedOilChangeAction($performedOilChangeRepository, $queryParamsFetcher);
    }
}
