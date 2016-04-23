<?php
namespace Rtaranto\Application\EndpointAction;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class DeleteSubResourceAction implements DeleteSubResourceActionInterface
{
    public function delete($parentResourceId, $subResourceId)
    {
        $subResource = $this->findOrThrowNotFound($parentResourceId, $subResourceId);
        $this->deleteSubResource($parentResourceId, $subResource);
    }
    
    public function findOrThrowNotFound($parentResourceId, $subResourceId)
    {
        $subResource = $this->findSubResource($parentResourceId, $subResourceId);
        
        if (empty($subResource)) {
            throw new NotFoundHttpException(
                sprintf('The resource of id \'%s\' was not found.', $subResourceId)
            );
        }
        
        return $subResource;
    }
    
    abstract protected function findSubResource($parentResourceId, $subResourceId);
    abstract protected function deleteSubResource($parentResourceId, $subResource);
}
