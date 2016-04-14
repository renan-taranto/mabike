<?php
namespace Rtaranto\Application\EndpointAction\Login;

use Rtaranto\Application\Command\Security\LoginCommand;
use Rtaranto\Application\Dto\Security\AuthenticationTokenDTO;
use Rtaranto\Application\Dto\Security\LoginDTO;
use Rtaranto\Application\EndpointAction\PostActionInterface;
use Rtaranto\Application\Exception\ValidationFailedException;
use Rtaranto\Application\ParametersBinder\ParametersBinderInterface;
use Rtaranto\Application\Service\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class PostLoginAction implements PostActionInterface
{
    /**
     * @var LoginCommand 
     */
    private $loginCommand;
    
    /**
     * @var ParametersBinderInterface 
     */
    private $parametersBinder;
    
    /**
     * @var ValidatorInterface 
     */
    private $validator;
    
    /**
     * @param LoginCommand $loginCommand
     * @param ParametersBinderInterface $parametersBinder
     * @param ValidatorInterface $validator
     */
    public function __construct(
        LoginCommand $loginCommand,
        ParametersBinderInterface $parametersBinder,
        ValidatorInterface $validator
    ) {
        $this->loginCommand = $loginCommand;
        $this->parametersBinder = $parametersBinder;
        $this->validator = $validator;
    }
    
    /**
     * @param array $requestBodyParameters
     * @return AuthenticationTokenDTO
     * @throws ValidationFailedException In case submitted data is invalid
     * @throws BadCredentialsException
     */
    public function post(array $requestBodyParameters)
    {
        $loginDTO = $this->parametersBinder->bind($requestBodyParameters, new LoginDTO());
        $this->validator->throwValidationFailedIfNotValid($loginDTO);
        
        return $this->loginCommand->execute($loginDTO);
    }
}
