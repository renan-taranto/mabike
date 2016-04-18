<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Rtaranto\Application\EndpointAction\Factory\CgetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\OilChange\CgetOilChangeAction;
use Rtaranto\Domain\Entity\MaintenancePerformer;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;

class CgetOilChangeActionFactory implements CgetActionFactoryInterface
{
    private $em;
    
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
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher);
        $maintenancePerformerRepository = $this->em->getRepository(MaintenancePerformer::class);
        return new CgetOilChangeAction($maintenancePerformerRepository, $queryParamsFetcher);
    }
}
