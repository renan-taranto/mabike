<?php
namespace Rtaranto\Application\EndpointAction;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class GetSubResourceAction implements GetSubresourceActionInterface
{
    public function get($parentResourceId, $subResourceId)
    {
        $subResource = $this->findSubResourceByParentResource($parentResourceId, $subResourceId);
        
        if (empty($subResource)) {
            throw new NotFoundHttpException(
                sprintf('The resource of id \'%s\' was not found.', $subResourceId)
            );
        }
        
        return $subResource;
    }
    
    abstract protected function findSubResourceByParentResource($parentResource, $subResourceId);
}
