<?php
namespace Rtaranto\Application\EndpointAction;

interface PostActionInterface
{
    public function post(array $requestBodyParameters);
}
