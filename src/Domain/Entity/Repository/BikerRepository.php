<?php
namespace Domain\Entity\Repository;

use Domain\Entity\Biker;

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
}
