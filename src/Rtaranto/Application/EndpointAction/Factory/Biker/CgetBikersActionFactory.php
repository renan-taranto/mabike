<?php
namespace Rtaranto\Application\EndpointAction\Factory\Biker;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Rtaranto\Application\EndpointAction\Biker\CgetBikersAction;
use Rtaranto\Application\EndpointAction\Factory\CgetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\FiltersNormalizer;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;

class CgetBikersActionFactory implements CgetActionFactoryInterface
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function createCgetAction(ParamFetcherInterface $paramFetcher)
    {
        $doctrineBikerRepository = new DoctrineBikerRepository($this->em);
        $filtersNormalizer = new FiltersNormalizer();
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher, $filtersNormalizer);
        return new CgetBikersAction($doctrineBikerRepository, $queryParamsFetcher);
    }
}
