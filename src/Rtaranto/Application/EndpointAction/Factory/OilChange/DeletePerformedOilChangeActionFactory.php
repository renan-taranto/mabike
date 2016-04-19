<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\DeleteActionFactoryInterface;
use Rtaranto\Application\EndpointAction\OilChange\DeletePerformedOilChangeAction;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedOilChangeRepository;

class DeletePerformedOilChangeActionFactory implements DeleteActionFactoryInterface
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
     * @return DeletePerformedOilChangeAction
     */
    public function createDeleteAction()
    {
        $performedOilChangeRepository = new DoctrinePerformedOilChangeRepository($this->em);
        return new DeletePerformedOilChangeAction($performedOilChangeRepository);
    }
}
