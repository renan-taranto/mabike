<?php
namespace Application\Service\Endpoint\Action\Biker;

interface BikersPutActionInterface
{
    public function put($id, $name, $email);
}
