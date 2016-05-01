<?php
namespace Rtaranto\Application\EndpointAction;

abstract class CgetSubResourceAction implements CgetSubresourceActionInterface
{
    public function cGet(
        $parentResourceId,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    ) {
        return $this->findAllSubResources($parentResourceId, $filters, $orderBy, $limit, $offset);
    }
    
    abstract protected function findAllSubResources($parentResourceId, $filters, $orderBy, $limit, $offset);
}
