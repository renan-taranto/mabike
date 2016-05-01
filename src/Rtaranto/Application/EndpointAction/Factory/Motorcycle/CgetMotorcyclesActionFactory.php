<?php
namespace Rtaranto\Application\EndpointAction\Factory\Motorcycle;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\CgetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\Motorcycle\CgetMotorcyclesAction;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
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
    
    /**
     * @return CgetMotorcyclesAction
     */
    public function createCgetAction()
    {
        $doctrineBikerRepository = new DoctrineBikerRepository($this->em);
        $biker = $doctrineBikerRepository->findOneByUser($this->user);
        $doctrineMotorcycleRepository = new DoctrineMotorcycleRepository($this->em, $doctrineBikerRepository);
        return new CgetMotorcyclesAction($biker, $doctrineMotorcycleRepository);
    }
}
