<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;

class DoctrineMotorcycleRepository implements MotorcycleRepositoryInterface
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function get($id)
    {
        $doctrineEntityRepository = $this->getDoctrineEntityRepository();
        return $doctrineEntityRepository->find($id);
    }

    public function getAll($filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $doctrineEntityRepository = $this->getDoctrineEntityRepository();
        return $doctrineEntityRepository->findBy($filters, $orderBy, $limit, $offset);
    }
    
    public function add(Motorcycle $motorcycle)
    {
        $em = $this->em;
        $em->persist($motorcycle);
        $em->flush();
        return $motorcycle;
    }

    public function update(Motorcycle $motorcycle)
    {
        $this->em->flush($motorcycle);
        return $motorcycle;
    }
    
    public function delete($id)
    {
        $em = $this->em;
        $motorcycle = $this->get($id);
        $em->remove($motorcycle);
        $this->em->flush();
    }

    public function findAllByBiker(Biker $biker, $filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $filters = array_merge(array('biker' => $biker), $filters);
        
        return $this->getAll($filters, $orderBy, $limit, $offset);
    }
    
    public function findOneByBikerAndId(Biker $biker, $id)
    {
        $doctrineRepository = $this->getDoctrineEntityRepository();
        return $doctrineRepository->findOneBy(array('biker' => $biker, 'id' => $id));
    }
    
    private function getDoctrineEntityRepository()
    {
        return $this->em->getRepository('Domain:Motorcycle');
    }
}
