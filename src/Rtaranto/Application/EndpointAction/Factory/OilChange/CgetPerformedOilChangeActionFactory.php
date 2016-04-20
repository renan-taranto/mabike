<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Rtaranto\Application\EndpointAction\CgetSubResourceAction;
use Rtaranto\Application\EndpointAction\Factory\CgetActionFactoryInterface;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Infrastructure\Repository\DoctrineSubResourceRepository;
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
     * @return CgetSubResourceAction
     */
    public function createCgetAction(ParamFetcherInterface $paramFetcher)
    {
        $subResourceRepository = new DoctrineSubResourceRepository($this->em, 'motorcycle', PerformedOilChange::class);
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher);
        return new CgetSubResourceAction($subResourceRepository, $queryParamsFetcher);
    }
}
