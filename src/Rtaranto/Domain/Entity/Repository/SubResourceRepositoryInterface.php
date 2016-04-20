<?php
namespace Rtaranto\Domain\Entity\Repository;

interface SubResourceRepositoryInterface
{
    public function update($subResource);
    public function delete($subResource);
    public function findOneByParentResource($parentResource);
    public function findOneByParentResourceAndId($parentResource, $subResourceId);
    public function findAll($filters = array(), $orderBy = null, $limit = null, $offset = null);
    public function findAllByParentResource(
        $parentResource,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    );
}
