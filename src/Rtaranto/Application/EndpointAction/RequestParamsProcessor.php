<?php
namespace Rtaranto\Application\EndpointAction;

use Rtaranto\Application\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;

class RequestParamsProcessor implements InputProcessorInterface
{
    private $parametersBinder;
    private $validator;
    
    public function __construct(
        ParametersBinderInterface $parametersBinder,
        ValidatorInterface $validator
    ) {
        $this->parametersBinder = $parametersBinder;
        $this->validator = $validator;
    }
    
    public function processInput($data, $targetDTO, $ignoreMissingFields = false)
    {
        $dtoWithBindedData = $this->bindParamsToDTO($data, $targetDTO, $ignoreMissingFields);
        $this->validator->throwValidationFailedIfNotValid($dtoWithBindedData);
        return $dtoWithBindedData;
    }
    
    private function bindParamsToDTO($data, $targetDTO, $ignoreMissingFields)
    {
        if ($ignoreMissingFields) {
            $dtoWithBindedData = $this->parametersBinder->bindIgnoringMissingFields($data, $targetDTO);
            return $dtoWithBindedData;
        }
        
        $dtoWithBindedData = $this->parametersBinder->bind($data, $targetDTO);
        return $dtoWithBindedData;
    }
}
