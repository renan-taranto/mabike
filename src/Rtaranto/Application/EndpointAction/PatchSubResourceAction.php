<?php
namespace Rtaranto\Application\EndpointAction;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class PatchSubResourceAction implements PatchSubresourceActionInterface
{
    abstract public function patch($parentResourceId, $resourceId, array $requestBodyParameters);
    
    abstract protected function findSubResourceByParentResource($parentResourceId, $subResourceId);
    
    protected function findOrThrowNotFound($parentResourceId, $subResourceId)
    {
        $subResource = $this->findSubResourceByParentResource($parentResourceId, $subResourceId);
        
        if (empty($subResource)) {
            throw new NotFoundHttpException(
                sprintf('The resource of id \'%s\' was not found.', $subResourceId)
            );
        }
        
        return $subResource;
    }
    
}
