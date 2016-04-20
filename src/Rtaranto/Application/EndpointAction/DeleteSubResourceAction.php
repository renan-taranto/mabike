<?php
namespace Rtaranto\Application\EndpointAction;

use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteSubResourceAction implements DeleteSubResourceActionInterface
{
    private $subResourceRepository;

    public function __construct(SubResourceRepositoryInterface $subResourceRepository)
    {
        $this->subResourceRepository = $subResourceRepository;
    }
    
    public function delete($parentResourceId, $subResourceId)
    {
        $subResource = $this->findOrThrowNotFound($parentResourceId, $subResourceId);
        $this->subResourceRepository->delete($subResource);
    }
    
    public function findOrThrowNotFound($parentResourceId, $subResourceId)
    {
        $subResource = $this->subResourceRepository
            ->findOneByParentResourceAndId($parentResourceId, $subResourceId);
        
        if (empty($subResource)) {
            throw new NotFoundHttpException(
                sprintf('The resource of id \'%s\' was not found.', $subResourceId)
            );
        }
        
        return $subResource;
    }
}
