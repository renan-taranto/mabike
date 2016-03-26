<?php
namespace Domain\Entity\Repository;

use Domain\Entity\Biker;

interface BikerRepository
{
    public function add(Biker $biker);
    public function get($id);
}
