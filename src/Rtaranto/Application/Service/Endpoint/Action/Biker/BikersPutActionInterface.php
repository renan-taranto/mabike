<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Biker;

interface BikersPutActionInterface
{
    public function put($id, array $requestBodyParameters);
}
