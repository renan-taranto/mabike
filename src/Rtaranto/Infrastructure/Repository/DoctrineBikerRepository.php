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
    
    public function getAll()
    {
        $doctrineRepository = $this->getDoctrineUserRepository();
        return $doctrineRepository->findAll();
    }

    public function update(Biker $biker)
    {
        $this->em->flush($biker);
        return $biker;
    }
    
    /**
     * @return Repository
     */
    private function getDoctrineUserRepository()
    {
        return $this->em->getRepository('Domain:Biker');
    }
}
