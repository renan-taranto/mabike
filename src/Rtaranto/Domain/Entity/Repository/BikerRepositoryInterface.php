<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\Biker;

interface BikerRepositoryInterface
{
    /**
     * @param Biker $biker
     * @return Biker
     */
    public function add(Biker $biker);
    /**
     * @param integer $id
     * @return Biker
     */
    public function get($id);
    /**
     * @return array
     */
    public function getAll();
    /**
     * @param Biker $biker
     * @return Biker
     */
    public function update(Biker $biker);
}
