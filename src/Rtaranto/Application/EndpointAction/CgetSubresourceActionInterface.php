<?php
namespace Rtaranto\Application\EndpointAction;

interface CgetSubresourceActionInterface
{
    public function cGet(
        $parentResourceId,
        $filters = array(),
        $orderBy = null,
        $limit = null,
        $offset = null
    );
}
