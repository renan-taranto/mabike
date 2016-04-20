<?php
namespace Rtaranto\Application\EndpointAction;

use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetSubResourceAction implements GetSubresourceActionInterface
{
    private $subResourceRepositoryInterface;
    
    public function __construct(SubResourceRepositoryInterface $subResourceRepositoryInterface)
    {
        $this->subResourceRepositoryInterface = $subResourceRepositoryInterface;
    }
    
    public function get($parentResourceId, $subResourceId)
    {
        $subResource = $this->subResourceRepositoryInterface
            ->findOneByParentResourceAndId($parentResourceId, $subResourceId);
        
        if (empty($subResource)) {
            throw new NotFoundHttpException(
                sprintf('The resource of id \'%s\' was not found.', $subResourceId)
            );
        }
        
        return $subResource;
    }
}
