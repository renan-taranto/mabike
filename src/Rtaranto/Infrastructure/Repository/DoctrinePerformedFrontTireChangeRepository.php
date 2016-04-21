<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\PerformedFrontTireChange;
use Rtaranto\Domain\Entity\Repository\PerformedFrontTireChangeRepositoryInterface;

class DoctrinePerformedFrontTireChangeRepository implements PerformedFrontTireChangeRepositoryInterface
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
     * @param PerformedFrontTireChange $performedFrontTireChange
     * @return PerformedFrontTireChange
     */
    public function update(PerformedFrontTireChange $performedFrontTireChange)
    {
        $this->em->flush($performedFrontTireChange);
        return $performedFrontTireChange;
    }
    
    /**
     * @param PerformedFrontTireChange $performedFrontTireChange
     */
    public function delete(PerformedFrontTireChange $performedFrontTireChange)
    {
        $this->em->remove($performedFrontTireChange);
        $this->em->flush();
    }

    /**
     * @param object|int $motorcycle
     * @param array $filters
     * @param string $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAllByMotorcycle($motorcycle, $filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $filtersContainingMotorcycleFilter = array_merge(
            $filters,
            array('motorcycle' => $motorcycle)
        );
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findBy($filtersContainingMotorcycleFilter, $orderBy, $limit, $offset);
    }

    /**
     * @param Motorcycle|int $motorcycle
     * @param int $performedFrontTireChangeId
     * @return PerformedFrontTireChange
     */
    public function findOneByMotorcycleAndId($motorcycle, $performedFrontTireChangeId)
    {
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findOneBy(
            array('motorcycle' => $motorcycle, 'id' => $performedFrontTireChangeId)
        );
    }

    /**
     * @return ObjectRepository
     */
    protected function getDoctrineObjectRepository()
    {
        return $this->em->getRepository(PerformedFrontTireChange::class);
    }
}
