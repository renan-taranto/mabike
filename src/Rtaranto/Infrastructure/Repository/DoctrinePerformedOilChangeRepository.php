<?php
namespace Rtaranto\Infrastructure\Repository;

use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;

class DoctrinePerformedOilChangeRepository implements PerformedOilChangeRepositoryInterface
{
    private $em;
    
    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function findOneByMotorcycleAndId($motorcycle, $performedOilChangeId)
    {
        $doctrineEntityRepository = $this->getDoctrineEntityRepository();
        return $doctrineEntityRepository->findOneBy(array('motorcycle' => $motorcycle, 'id' => $performedOilChangeId));
    }
    
    public function findAll($filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $doctrineEntityRepository = $this->getDoctrineEntityRepository();
        return $doctrineEntityRepository->findBy($filters, $orderBy, $limit, $offset);
    }
    
    public function findAllByMotorcycle(
        $motorcycle,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    ) {
        $filters = array_merge($filters, array('motorcycle' => $motorcycle));
        return $this->findAll($filters, $orderBy, $limit, $offset);
    }
    
    private function getDoctrineEntityRepository()
    {
        return $this->em->getRepository('Domain:PerformedOilChange');
    }
}
