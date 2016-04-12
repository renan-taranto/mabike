<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DoctrineMotorcycleRepository implements MotorcycleRepositoryInterface
{
    private $em;
    private $bikerRepository;
    
    public function __construct(EntityManagerInterface $em, BikerRepositoryInterface $bikerRepository)
    {
        $this->em = $em;
        $this->bikerRepository = $bikerRepository;
    }
    
    public function get($id)
    {
        $doctrineEntityRepository = $this->getDoctrineEntityRepository();
        return $doctrineEntityRepository->find($id);
    }

    public function getAll($filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $doctrineEntityRepository = $this->getDoctrineEntityRepository();
        return $doctrineEntityRepository->findBy($filters, $orderBy, $limit, $offset);
    }
    
    public function add(Motorcycle $motorcycle)
    {
        $em = $this->em;
        $em->persist($motorcycle);
        $em->flush();
        return $motorcycle;
    }

    public function update(Motorcycle $motorcycle)
    {
        $em = $this->em;
        $em->flush($motorcycle);
        return $motorcycle;
    }
    
    public function delete($id)
    {
        $em = $this->em;
        $motorcycle = $this->get($id);
        $em->remove($motorcycle);
        $this->em->flush();
    }

    public function findAllByUser(UserInterface $user, $filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $biker = $this->bikerRepository->findOneByUser($user);
        
        if (empty($biker)) {
            return array();
        }
        
        $filters = array_merge(array('biker' => $biker), $filters);
        
        return $this->getAll($filters, $orderBy, $limit, $offset);
    }
    
    private function getDoctrineEntityRepository()
    {
        return $this->em->getRepository('Domain:Motorcycle');
    }
}
