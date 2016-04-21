<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\PerformedFrontTireChange;

interface PerformedFrontTireChangeRepositoryInterface
{
    public function update(PerformedFrontTireChange $performedFrontTireChange);
    public function delete(PerformedFrontTireChange $performedFrontTireChange);
    public function findOneByMotorcycleAndId($motorcycle, $performedFrontTireChangeId);
    public function findAllByMotorcycle(
        $motorcycle,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    );
}
