<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Repository;
use Rtaranto\Domain\Entity\Repository\UserRepositoryInterface;
use Rtaranto\Domain\Entity\User;

class DoctrineUserRepository implements UserRepositoryInterface
{
    private $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function addUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function updateUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function removeUser(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function query(array $valuesByFields)
    {
        $doctrineUserRepository = $this->getDoctrineUserRepository();
        return $doctrineUserRepository->findBy($valuesByFields);
    }

    /**
     * @return Repository
     */
    private function getDoctrineUserRepository()
    {
        return $this->em->getRepository('Domain:User');
    }

    public function findByUsername($username)
    {
        $doctrineUserRepository = $this->getDoctrineUserRepository();
        return $doctrineUserRepository->findOneBy(array('username' => $username));
    }
    
    public function findByApiKey($apiKey)
    {
        $doctrineUserRepository = $this->getDoctrineUserRepository();
        return $doctrineUserRepository->findOneBy(array('apiKey' => $apiKey));
    }

}
