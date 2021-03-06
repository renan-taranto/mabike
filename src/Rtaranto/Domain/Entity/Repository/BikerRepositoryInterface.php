<?php
namespace Rtaranto\Domain\Entity\Repository;

use Rtaranto\Domain\Entity\Biker;
use Rtaranto\Domain\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

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
    public function getAll($filters = array(), $orderBy = null, $limit = null, $offset = null);
    /**
     * @param Biker $biker
     * @return Biker
     */
    public function update(Biker $biker);
    /**
     * @param integer $id
     */
    public function delete($id);
    
    /**
     * @param User $user
     * @return Biker
     */
    public function findOneByUser(UserInterface $user);
}
