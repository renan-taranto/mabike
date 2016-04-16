<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Exception;
use Rtaranto\Application\Dto\Maintenance\PerformedMaintenanceDTO;
use Rtaranto\Application\EndpointAction\PostSubresourceActionInterface;
use Rtaranto\Application\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\MaintenancePerformer;
use Rtaranto\Domain\Entity\Repository\MaintenancePerformerRepositoryInterface;

class PostOilChangeAction implements PostSubresourceActionInterface
{
    private $parametersBinder;
    private $validator;
    private $maintenancePerformerRepository;
    
    public function __construct(
        ParametersBinderInterface $parametersBinder,
        ValidatorInterface $validator,
        MaintenancePerformerRepositoryInterface $maintenancePerformerRepository
    ) {
        $this->parametersBinder = $parametersBinder;
        $this->validator = $validator;
        $this->maintenancePerformerRepository = $maintenancePerformerRepository;
    }
    
    public function post($parentResourceId, array $requestBodyParameters)
    {
        /* @var $maintenanceDTO PerformedMaintenanceDTO */
        $maintenanceDTO = $this->parametersBinder->bind($requestBodyParameters, new PerformedMaintenanceDTO());
        $this->validator->throwValidationFailedIfNotValid($maintenanceDTO);
        
        $maintenacePerformer = $this->getMaintenancePerformer($parentResourceId);
        $oilChangePerformed = $maintenacePerformer->changeOil($maintenanceDTO->getKmsDriven(), $maintenanceDTO->getDate());
        $this->validator->throwValidationFailedIfNotValid($maintenacePerformer);
        
        $this->maintenancePerformerRepository->update($maintenacePerformer);
        
        return $oilChangePerformed;
    }
    
    /**
     * @param int $motorcycleId
     * @return MaintenancePerformer
     * @throws Exception
     */
    private function getMaintenancePerformer($motorcycleId)
    {
        $maintenancePerformer = $this->maintenancePerformerRepository
            ->findByMotorcycle($motorcycleId);
        if (empty($maintenancePerformer)) {
            throw new Exception('OilChangePerformer not found.');
        }
        
        return $maintenancePerformer;
    }
}
