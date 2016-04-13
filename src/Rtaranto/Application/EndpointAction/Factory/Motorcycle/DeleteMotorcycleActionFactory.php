<?php
namespace Rtaranto\Application\EndpointAction\Factory\Motorcycle;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\DeleteActionFactoryInterface;
use Rtaranto\Application\EndpointAction\Motorcycle\DeleteMotorcycleAction;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class DeleteMotorcycleActionFactory implements DeleteActionFactoryInterface
{
    /**
     * @var UserInterface
     */
    private $user;
    
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @param UserInterface $user
     * @param EntityManagerInterface $em
     */
    public function __construct(UserInterface $user, EntityManagerInterface $em)
    {
        $this->user = $user;
        $this->em = $em;
    }
    
    /**
     * @return DeleteMotorcycleAction
     */
    public function createDeleteAction()
    {
        $bikerRepository = new DoctrineBikerRepository($this->em);
        $biker = $bikerRepository->findOneByUser($this->user);
        $motorcycleRepository = new DoctrineMotorcycleRepository($this->em);
        return new DeleteMotorcycleAction($biker, $motorcycleRepository);
    }
}
