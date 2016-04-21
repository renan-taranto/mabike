<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\Repository\FrontTireChangeRepositoryInterface;

class DoctrineFrontTireChangeRepository implements FrontTireChangeRepositoryInterface
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

    public function update(FrontTireChange $frontTireChange)
    {
        $this->em->flush($frontTireChange);
        return $frontTireChange;
    }
    
    /**
     * @return ObjectRepository
     */
    protected function getDoctrineObjectRepository()
    {
        return $this->em->getRepository(FrontTireChange::class);
    }

}
