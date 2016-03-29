<?php

namespace Infrastructure\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
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
    
    public function addAtId(Biker $biker, $id)
    {
        $biker->setId($id);
        $this->em->persist($biker);
        $metadata = $this->em->getClassMetadata(Biker::class);
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
        $this->em->flush();
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
