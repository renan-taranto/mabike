<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\GetActionFactoryInterface;
use Rtaranto\Application\EndpointAction\GetSubResourceAction;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Infrastructure\Repository\DoctrineSubResourceRepository;

class GetPerformedOilChangeActionFactory implements GetActionFactoryInterface
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
    
    public function createGetAction()
    {
        $subResourceRepository = new DoctrineSubResourceRepository($this->em, 'motorcycle', PerformedOilChange::class);
        return new GetSubResourceAction($subResourceRepository);
    }

}
