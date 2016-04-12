<?php
namespace Rtaranto\Application\EndpointAction\Factory\Motorcycle;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Rtaranto\Application\EndpointAction\Factory\CgetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\Motorcycle\CgetMotorcyclesAction;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Rtaranto\Presentation\Controller\QueryParam\QueryParamsFetcher;
use Symfony\Component\Security\Core\User\UserInterface;

class CgetMotorcyclesActionFactory implements CgetActionFactoryInterface
{
    private $em;
    private $user;
    
    public function __construct(EntityManagerInterface $em, UserInterface $user)
    {
        $this->em = $em;
        $this->user = $user;
    }
    
    public function createCgetAction(ParamFetcherInterface $paramFetcher)
    {
        $doctrineBikerRepository = new DoctrineBikerRepository($this->em);
        $doctrineMotorcycleRepository = new DoctrineMotorcycleRepository($this->em, $doctrineBikerRepository);
        $queryParamsFetcher = new QueryParamsFetcher($paramFetcher);
        return new CgetMotorcyclesAction($this->user, $doctrineMotorcycleRepository, $queryParamsFetcher);
    }
}
