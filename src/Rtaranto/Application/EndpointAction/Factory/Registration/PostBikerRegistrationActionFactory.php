<?php
namespace Rtaranto\Application\EndpointAction\Factory\Registration;

use Rtaranto\Application\Command\Security\BikerRegistrationCommand;
use Rtaranto\Application\EndpointAction\Factory\PostActionFactoryInterface;
use Rtaranto\Application\EndpointAction\Registration\PostBikerRegistrationAction;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Security\BikerRegistrationService;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Presentation\Form\UserRegistrationDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostBikerRegistrationActionFactory implements PostActionFactoryInterface
{
    private $formFactory;
    private $sfValidator;
    private $bikerRegistrationService;
    
    public function __construct(
        FormFactoryInterface $formFactory,
        ValidatorInterface $sfValidator,
        BikerRegistrationService $bikerRegistrationService
    ) {
        $this->formFactory = $formFactory;
        $this->sfValidator = $sfValidator;
        $this->bikerRegistrationService = $bikerRegistrationService;
    }
    
    /**
     * @return PostBikerRegistrationAction
     */
    public function createPostAction()
    {
        $parametersBinder = new ParametersBinder($this->formFactory, UserRegistrationDTOType::class);
        $validator = new Validator($this->sfValidator);
        $bikerRegistrationCommand = new BikerRegistrationCommand($this->bikerRegistrationService);
        
        return new PostBikerRegistrationAction($parametersBinder, $validator, $bikerRegistrationCommand);
    }
}
