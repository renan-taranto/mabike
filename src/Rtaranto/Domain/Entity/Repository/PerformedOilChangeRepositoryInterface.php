<?php
namespace Rtaranto\Domain\Entity\Repository;

interface PerformedOilChangeRepositoryInterface
{
    public function findOneByMotorcycleAndId($motorcycle, $performedOilChangeId);
    public function findAll($filters = array(), $orderBy = null, $limit = null, $offset = null);
    public function findAllByMotorcycle($motorcycle);    
}
