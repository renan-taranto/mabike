<?php
namespace Rtaranto\Application\EndpointAction\Factory\Biker;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Biker\CgetBikersAction;
use Rtaranto\Application\EndpointAction\Factory\CgetActionFactoryInterface;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;

class CgetBikersActionFactory implements CgetActionFactoryInterface
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function createCgetAction()
    {
        $doctrineBikerRepository = new DoctrineBikerRepository($this->em);
        return new CgetBikersAction($doctrineBikerRepository);
    }
}
