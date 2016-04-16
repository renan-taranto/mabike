<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\ORM\EntityRepository;
use Rtaranto\Domain\Entity\MaintenancePerformer;
use Rtaranto\Domain\Entity\Repository\OilChangePerformerRepositoryInterface;

class DoctrineOilChangePerformerRepository extends EntityRepository implements OilChangePerformerRepositoryInterface
{
    public function add(MaintenancePerformer $maintenancePerformer)
    {
        $em = $this->getEntityManager();
        $em->persist($maintenancePerformer);
        $em->flush();
        return $maintenancePerformer;
    }

    public function get($id)
    {
        return $this->find($id);
    }

    public function getAll($filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        return $this->findBy($filters, $orderBy, $limit, $offset);
    }

    public function update(MaintenancePerformer $maintenancePerformer)
    {
        $em = $this->getEntityManager();
        $em->flush($maintenancePerformer);
        return $maintenancePerformer;
    }

    public function findByMotorcycle($motorcycle)
    {
        return $this->findOneBy(array('motorcycle' => $motorcycle));
    }
}
