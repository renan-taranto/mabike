<?php
namespace Rtaranto\Application\EndpointAction\OilChange;

use Exception;
use Rtaranto\Application\Dto\Maintenance\MaintenanceDTO;
use Rtaranto\Application\EndpointAction\PostSubresourceActionInterface;
use Rtaranto\Application\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Rtaranto\Domain\Entity\OilChangePerformer;
use Rtaranto\Domain\Entity\Repository\OilChangePerformerRepositoryInterface;

class BikerPostOilChangeAction implements PostSubresourceActionInterface
{
    private $parametersBinder;
    private $validator;
    private $oilChangePerformerRepository;
    
    public function __construct(
        ParametersBinderInterface $parametersBinder,
        ValidatorInterface $validator,
        OilChangePerformerRepositoryInterface $oilChangePerformerRepository
    ) {
        $this->parametersBinder = $parametersBinder;
        $this->validator = $validator;
        $this->oilChangePerformerRepository = $oilChangePerformerRepository;
    }
    
    public function post($parentResourceId, array $requestBodyParameters)
    {
        /* @var $maintenanceDTO MaintenanceDTO */
        $maintenanceDTO = $this->parametersBinder->bind($requestBodyParameters, new MaintenanceDTO());
        $this->validator->throwValidationFailedIfNotValid($maintenanceDTO);
        
        $oilChangePerformer = $this->getOilChangePerformer($parentResourceId);
        $oilChange = $oilChangePerformer->changeOil($maintenanceDTO->getKmsDriven(), $maintenanceDTO->getDate());
        $this->validator->throwValidationFailedIfNotValid($oilChange);
        
        $this->oilChangePerformerRepository->update($oilChangePerformer);
        return $oilChange;
    }
    
    /**
     * @param int $motorcycleId
     * @return OilChangePerformer
     * @throws Exception
     */
    private function getOilChangePerformer($motorcycleId)
    {
        $oilChangePerformer = $this->oilChangePerformerRepository
            ->findByMotorcycle($motorcycleId);
        if (empty($oilChangePerformer)) {
            throw new Exception('OilChangePerformer not found.');
        }
        
        return $oilChangePerformer;
    }
}
