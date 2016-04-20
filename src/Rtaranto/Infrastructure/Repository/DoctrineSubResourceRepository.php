<?php
namespace Rtaranto\Infrastructure\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;

class DoctrineSubResourceRepository implements SubResourceRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @var string
     */
    private $parentEntityFieldName;
    
    /**
     * @var string
     */
    private $entityClassName;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, $parentEntityFieldName, $entityClassName)
    {
        $this->em = $em;
        $this->parentEntityFieldName = $parentEntityFieldName;
        $this->entityClassName = $entityClassName;
    }

    public function update($subResource)
    {
        $this->em->flush($subResource);
        return $subResource;
    }
    
    public function delete($subResource)
    {
        $this->em->remove($subResource);
        $this->em->flush();
    }
    
    /**
     * @param array $filters
     * @param string $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAll($filters = array(), $orderBy = null, $limit = null, $offset = null)
    {
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findBy($filters, $orderBy, $limit, $offset);
    }

    /**
     * @param Object|id $parentResource
     * @param array $filters
     * @param string $orderBy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAllByParentResource(
        $parentResource,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    ) {
        $filtersContainingMotorcycleFilter = array_merge(
            $filters,
            array($this->parentEntityFieldName => $parentResource)
        );
        return $this->findAll($filtersContainingMotorcycleFilter, $orderBy, $limit, $offset);
    }

    public function findOneByParentResource($parentResource)
    {
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findOneBy(
            array($this->parentEntityFieldName => $parentResource)
        );
    }
    
    public function findOneByParentResourceAndId($parentResource, $subResourceId)
    {
        $doctrineObjectRepository = $this->getDoctrineObjectRepository();
        return $doctrineObjectRepository->findOneBy(
            array($this->parentEntityFieldName => $parentResource, 'id' => $subResourceId)
        );
    }

    /**
     * @return ObjectRepository
     */
    protected function getDoctrineObjectRepository()
    {
        return $this->em->getRepository($this->entityClassName);
    }
}
