<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;

class DoctrinePerformedOilChangeRepository implements PerformedOilChangeRepositoryInterface
{
    private $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return ObjectRepository
     */
    protected function getDoctrineObjectRepository()
    {
        return $this->em->getRepository(PerformedOilChange::class);
    }

    public function delete(PerformedOilChange $performedOilChange)
    {
        $this->em->remove($performedOilChange);
        $this->em->flush();
    }

    public function findAllByMotorcycle($motorcycle, $filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $filtersContainingMotorcycleFilter = array_merge(
            $filters,
            array('motorcycle' => $motorcycle)
        );
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findBy($filtersContainingMotorcycleFilter, $orderBy, $limit, $offset);
    }

    public function findOneByMotorcycleAndId($motorcycle, $performedOilChange)
    {
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findOneBy(
            array('motorcycle' => $motorcycle, 'id' => $performedOilChange)
        );
    }

    public function update(PerformedOilChange $performedOilChange)
    {
        $this->em->flush($performedOilChange);
        return $performedOilChange;
    }

}
