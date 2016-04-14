<?php
namespace Rtaranto\Application\EndpointAction\Registration;

use Rtaranto\Application\Command\Security\BikerRegistrationCommand;
use Rtaranto\Application\Dto\Security\UserRegistrationDTO;
use Rtaranto\Application\EndpointAction\PostActionInterface;
use Rtaranto\Application\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;

class PostBikerRegistrationAction implements PostActionInterface
{
    private $parametersBinder;
    private $validator;
    private $bikerRegistrationCommand;

    public function __construct(
        ParametersBinderInterface $parametersBinder,
        ValidatorInterface $validator,
        BikerRegistrationCommand $bikerRegistrationCommand
    ) {
        $this->parametersBinder = $parametersBinder;
        $this->validator = $validator;
        $this->bikerRegistrationCommand = $bikerRegistrationCommand;
    }

    public function post(array $requestBodyParameters)
    {
        $userRegistrationDTO = $this->parametersBinder->bind($requestBodyParameters, new UserRegistrationDTO());
        $this->validator->throwValidationFailedIfNotValid($userRegistrationDTO);
        return $this->bikerRegistrationCommand->execute($userRegistrationDTO);
    }
}
