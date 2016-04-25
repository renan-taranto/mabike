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
    
    public function processInput($data, $targetDTO)
    {
        $dtoWithBindedData = $this->parametersBinder->bind($data, $targetDTO);
        $this->validator->throwValidationFailedIfNotValid($dtoWithBindedData);
        return $dtoWithBindedData;
    }
    
    public function processInputIgnoringMissingFields($data, $targetDTO)
    {
        $dtoWithBindedData = $this->parametersBinder->bindIgnoringMissingFields($data, $targetDTO);
        $this->validator->throwValidationFailedIfNotValid($dtoWithBindedData);
        return $dtoWithBindedData;
    }
}
