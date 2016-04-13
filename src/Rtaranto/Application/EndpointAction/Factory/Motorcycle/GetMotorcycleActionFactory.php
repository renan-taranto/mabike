<?php
namespace Rtaranto\Application\EndpointAction\Factory\Motorcycle;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\GetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\Motorcycle\GetMotorcycleAction;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class GetMotorcycleActionFactory implements GetActionFactoryInterface
{
    private $user;
    private $em;
    
    public function __construct(UserInterface $user, EntityManagerInterface $em)
    {
        $this->user = $user;
        $this->em = $em;
    }
    
    public function createGetAction()
    {
        $bikerRepository = new DoctrineBikerRepository($this->em);
        $motorcycleRepository = new DoctrineMotorcycleRepository($this->em);
        $biker = $bikerRepository->findOneByUser($this->user);
        return new GetMotorcycleAction($motorcycleRepository, $biker);
    }
}
