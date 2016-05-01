<?php
namespace Rtaranto\Application\EndpointAction;

interface CgetActionInterface
{
    public function cGet($filters = array(), $orderBy = null, $limit = null, $offset = null);
}
