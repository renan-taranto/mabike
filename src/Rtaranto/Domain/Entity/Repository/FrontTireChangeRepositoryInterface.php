<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\FrontTireChange;

interface FrontTireChangeRepositoryInterface
{
    public function update(FrontTireChange $frontTireChange);
    public function findOneByMotorcycle($motorcycle);
}
