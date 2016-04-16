<?php
namespace Rtaranto\Application\EndpointAction;

interface PostSubresourceActionInterface
{
    public function post($parentResourceId, array $requestBodyParameters);
}
