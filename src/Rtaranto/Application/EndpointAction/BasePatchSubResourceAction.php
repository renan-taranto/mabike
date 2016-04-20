<?php
namespace Rtaranto\Application\EndpointAction;

use Rtaranto\Domain\Entity\Repository\SubResourceRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BasePatchSubResourceAction
{
    protected $subResourceRepository;
    
    public function __construct(SubResourceRepositoryInterface $subResourceRepository)
    {
        $this->subResourceRepository = $subResourceRepository;
    }
    
    protected function findOrThrowNotFound($parendResourceId, $subResourceId)
    {
        $subResource = $this->subResourceRepository
            ->findOneByParentResourceAndId($parendResourceId, $subResourceId);
        
        if (empty($subResource)) {
            throw new NotFoundHttpException(
                sprintf('The resource of id \'%s\' was not found.', $subResourceId)
            );
        }
        
        return $subResource;
    }
}
