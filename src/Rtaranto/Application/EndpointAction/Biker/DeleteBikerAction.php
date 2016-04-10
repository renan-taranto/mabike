<?php
namespace Rtaranto\Application\EndpointAction\Biker;

use Rtaranto\Application\EndpointAction\DeleteActionInterface;
use Rtaranto\Domain\Entity\Repository\BikerRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteBikerAction implements DeleteActionInterface
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
