<?php
namespace Rtaranto\Application\EndpointAction;

interface PostActionInterface
{
    /**
     * @param array $requestBodyParameters
     * @return Biker $biker
     */
    public function post(array $requestBodyParameters);
}
