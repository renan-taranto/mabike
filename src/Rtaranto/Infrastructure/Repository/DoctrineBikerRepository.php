<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\ORM\EntityManager;
use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;

class DoctrineBikerRepository implements BikerRepositoryInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function add(Biker $biker)
    {
        $this->em->persist($biker);
        $this->em->flush();
        return $biker;
    }

    public function get($id)
    {
        $doctrineRepository = $this->getDoctrineUserRepository();
        return $doctrineRepository->find($id);
    }
    
    public function getAll($filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $doctrineRepository = $this->getDoctrineUserRepository();
        return $doctrineRepository->findBy($filters, $orderBy, $limit, $offset);
    }

    public function update(Biker $biker)
    {
        $this->em->flush($biker);
        return $biker;
    }

    public function delete($id)
    {
        $biker = $this->get($id);
        $this->em->remove($biker);
        $this->em->flush();
    }
    
    /**
     * @return Repository
     */
    private function getDoctrineUserRepository()
    {
        return $this->em->getRepository('Domain:Biker');
    }
}
