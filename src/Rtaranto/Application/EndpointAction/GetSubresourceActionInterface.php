<?php
namespace Rtaranto\Application\EndpointAction;

interface GetSubresourceActionInterface
{
    public function get($parentResourceId, $resourceId);
}
