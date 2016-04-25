<?php
namespace Rtaranto\Application\EndpointAction\Motorcycle;

use Rtaranto\Application\Dto\Motorcycle\MotorcycleDTO;
use Rtaranto\Application\EndpointAction\InputProcessorInterface;
use Rtaranto\Application\EndpointAction\PatchActionInterface;
use Rtaranto\Application\Service\Motorcycle\MotorcyclePatcherInterface;
use Rtaranto\Domain\Entity\Motorcycle;
use Rtaranto\Domain\Entity\Repository\MotorcycleRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PatchMotorcycleAction implements PatchActionInterface
{
    private $inputProcessor;
    private $motorcyclePatcher;        
    private $motorcycleRepository;
    
    public function __construct(
        InputProcessorInterface $inputProcessor,
        MotorcyclePatcherInterface $motorcyclePatcher,
        MotorcycleRepositoryInterface $motorcycleRepository
    ) {
        $this->inputProcessor = $inputProcessor;
        $this->motorcyclePatcher = $motorcyclePatcher;
        $this->motorcycleRepository = $motorcycleRepository;
    }
    
    /**
     * @param int $id
     * @param array $requestBodyParameters
     * @return Motorcycle
     */
    public function patch($id, $requestBodyParameters)
    {
        $motorcycle = $this->findOrThrowNotFound($id);
        
        $motorcycleDTO = new MotorcycleDTO($motorcycle->getModel(), $motorcycle->getKmsDriven());
        $patchedMotorcycleDTO = $this->inputProcessor->processInputIgnoringMissingFields($requestBodyParameters, $motorcycleDTO);
        
        return $this->motorcyclePatcher->patchMotorcycle($motorcycle, $patchedMotorcycleDTO);
    }
    
    /**
     * @param int $id
     * @return Motorcycle
     * @throws NotFoundHttpException
     */
    private function findOrThrowNotFound($id)
    {
        $motorcycle = $this->motorcycleRepository->get($id);
        
        if (empty($motorcycle)) {
            throw new NotFoundHttpException(
                sprintf('The Motorcycle resource of id \'%s\' was not found.', $id)
            );
        }
        
        return $motorcycle;
    }
}
