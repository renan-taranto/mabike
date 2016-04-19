<?php
namespace Rtaranto\Application\EndpointAction;

interface PatchSubresourceActionInterface
{
    public function patch($parentResourceId, $resourceId, array $requestBodyParameters);
}
