<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class DoctrineRepository
{
    protected $em;
    protected $entityClassName;
    
    public function __construct(EntityManagerInterface $em, $entityClassName)
    {
        $this->em = $em;
        $this->entityClassName = $entityClassName;
    }
    
    /**
     * @return ObjectRepository
     */
    protected function getDoctrineObjectRepository()
    {
        return $this->em->getRepository($this->entityClassName);
    }
}
