<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\Biker;

interface BikerRepository
{
    public function add(Biker $biker);
    /**
     * @param integer $id
     * @return Biker
     */
    public function get($id);
    public function getAll();
    public function update(Biker $biker);
    public function addAtId(Biker $biker, $id);
}
