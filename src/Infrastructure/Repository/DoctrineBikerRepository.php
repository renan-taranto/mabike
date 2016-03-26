<?php

namespace Infrastructure\Repository;

use Doctrine\ORM\EntityManager;
use Domain\Entity\Biker;
use Domain\Entity\Repository\BikerRepository;

class DoctrineBikerRepository implements BikerRepository
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

    /**
     * @return Repository
     */
    private function getDoctrineUserRepository()
    {
        return $this->em->getRepository('Domain:Biker');
    }

}
