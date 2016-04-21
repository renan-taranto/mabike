<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Domain\Entity\Repository\RearTireChangeRepositoryInterface;

class DoctrineRearTireChangeRepository implements RearTireChangeRepositoryInterface
{
    private $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function findOneByMotorcycle($motorcycle)
    {
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findOneBy(
            array('motorcycle' => $motorcycle)
        );
    }

    public function update(RearTireChange $rearTireChange)
    {
        $this->em->flush($rearTireChange);
        return $rearTireChange;
    }
    
    /**
     * @return ObjectRepository
     */
    protected function getDoctrineObjectRepository()
    {
        return $this->em->getRepository(RearTireChange::class);
    }

}
