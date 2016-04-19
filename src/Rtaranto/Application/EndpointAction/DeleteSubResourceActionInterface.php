<?php
namespace Rtaranto\Application\EndpointAction;

interface DeleteSubResourceActionInterface
{
    public function delete($parentResourceId, $subResourceId);
}
