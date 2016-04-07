<?php
namespace Rtaranto\Application\Service\Endpoint\Action\Biker;

use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteBiker implements DeleteBikerInterface
{
    private $bikerRepository;

    public function __construct(BikerRepositoryInterface $bikerRepository)
    {
        $this->bikerRepository = $bikerRepository;
    }

    public function delete($id)
    {
        $this->throwExceptionIfBikerNotFound($id);
        $this->bikerRepository->delete($id);
    }

    /**
     * @param integer $id
     * @throws NotFoundHttpException
     */
    private function throwExceptionIfBikerNotFound($id)
    {
        if (empty($this->bikerRepository->get($id))) {
            throw new NotFoundHttpException(
                sprintf('The Biker resource of id \'%s\' was not found.', $id));
        }
    }
}
