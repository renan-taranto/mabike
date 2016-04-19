<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\PerformedOilChange;
use Rtaranto\Domain\Entity\Repository\PerformedOilChangeRepositoryInterface;

class DoctrinePerformedOilChangeRepository implements PerformedOilChangeRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * @param PerformedOilChange $performedOilChange
     * @return PerformedOilChange
     */
    public function update(PerformedOilChange $performedOilChange)
    {
        $this->em->flush($performedOilChange);
        return $performedOilChange;
    }
    
    public function delete($performedOilChange)
    {
        $this->em->remove($performedOilChange);
        $this->em->flush();
    }
    
    /**
     * @param Motorcycle|int $motorcycle
     * @param int $performedOilChangeId
     * @return PerformedOilChange
     */
    public function findOneByMotorcycleAndId($motorcycle, $performedOilChangeId)
    {
        $doctrineEntityRepository = $this->getDoctrineEntityRepository();
        return $doctrineEntityRepository->findOneBy(array('motorcycle' => $motorcycle, 'id' => $performedOilChangeId));
    }
    
    /**
     * @param array $filters
     * @param string $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAll($filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $doctrineEntityRepository = $this->getDoctrineEntityRepository();
        return $doctrineEntityRepository->findBy($filters, $orderBy, $limit, $offset);
    }
    
    /**
     * @param Motorcycle|int $motorcycle
     * @param array $filters
     * @param string $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAllByMotorcycle(
        $motorcycle,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    ) {
        $filters = array_merge($filters, array('motorcycle' => $motorcycle));
        return $this->findAll($filters, $orderBy, $limit, $offset);
    }
    
    /**
     * @return ObjectRepository
     */
    private function getDoctrineEntityRepository()
    {
        return $this->em->getRepository('Domain:PerformedOilChange');
    }
}
