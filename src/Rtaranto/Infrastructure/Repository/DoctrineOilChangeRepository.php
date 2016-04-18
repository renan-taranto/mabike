<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\Repository\OilChangeRepositoryInterface;

class DoctrineOilChangeRepository implements OilChangeRepositoryInterface
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function update(OilChange $oilChange)
    {
        $this->em->flush($oilChange);
        return $oilChange;
    }
    
    public function findOneByMotorcycle($motorcycle)
    {
        $doctrineEntityRepository = $this->getDoctrineEntityRepository();
        return $doctrineEntityRepository->findOneBy(array('motorcycle' => $motorcycle));
    }
    
    private function getDoctrineEntityRepository()
    {
        return $this->em->getRepository('Domain:OilChange');
    }
}
