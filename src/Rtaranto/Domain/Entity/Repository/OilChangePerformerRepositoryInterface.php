<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\MaintenancePerformer;

interface OilChangePerformerRepositoryInterface
{
    public function add(MaintenancePerformer $maintenancePerformer);

    public function get($id);

    public function getAll($filters = array(), $orderBy = null, $limit = null, $offset = null);

    public function update(MaintenancePerformer $maintenancePerformer);
    
    public function findByMotorcycle($motorcycle);
}
