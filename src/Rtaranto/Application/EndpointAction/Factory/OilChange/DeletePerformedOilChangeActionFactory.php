<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\DeleteSubResourceAction;
use Rtaranto\Application\EndpointAction\Factory\DeleteActionFactoryInterface;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Infrastructure\Repository\DoctrineSubResourceRepository;

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
     * @return DeleteSubResourceAction
     */
    public function createDeleteAction()
    {
        $subResourceRepository = new DoctrineSubResourceRepository($this->em, 'motorcycle', PerformedOilChange::class);
        return new DeleteSubResourceAction($subResourceRepository);
    }
}
