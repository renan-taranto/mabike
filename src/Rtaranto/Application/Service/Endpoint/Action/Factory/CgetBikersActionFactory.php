<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Factory;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Rtaranto\Application\Service\Endpoint\Action\Biker\BikersCgetAction;
use Rtaranto\Application\Service\Endpoint\Action\Factory\CgetActionFactoryInterface;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;

class CgetBikersActionFactory implements CgetActionFactoryInterface
{
    public function createCgetAction(EntityManagerInterface $em, ParamFetcherInterface $paramFetcher)
    {
        $doctrineBikerRepository = new DoctrineBikerRepository($em);
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher);
        return new BikersCgetAction($doctrineBikerRepository, $queryParamsFetcher);
    }
}
