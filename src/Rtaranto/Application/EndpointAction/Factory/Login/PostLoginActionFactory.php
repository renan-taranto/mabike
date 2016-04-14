<?php
namespace Rtaranto\Application\EndpointAction\Factory\Login;

use Rtaranto\Application\Command\Security\LoginCommand;
use Rtaranto\Application\EndpointAction\Factory\PostActionFactoryInterface;
use Rtaranto\Application\EndpointAction\Login\PostLoginAction;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Security\LoginServiceInterface;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Presentation\Form\Login\LoginDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostLoginActionFactory implements PostActionFactoryInterface
{
    /**
     * @var LoginServiceInterface 
     */
    private $loginService;
    
    /**
     * @var FormFactoryInterface 
     */
    private $formFactory;
    
    /**
     * @var ValidatorInterface 
     */
    private $sfValidator;
    
    /**
     * @param LoginServiceInterface $loginService
     * @param FormFactoryInterface $formFactory
     * @param ValidatorInterface $sfValidator
     */
    public function __construct(
        LoginServiceInterface $loginService,
        FormFactoryInterface $formFactory,
        ValidatorInterface $sfValidator
    ) {
        $this->loginService = $loginService;
        $this->formFactory = $formFactory;
        $this->sfValidator = $sfValidator;
    }
    
    /**
     * @return PostLoginAction
     */
    public function createPostAction()
    {
        $loginCommand = new LoginCommand($this->loginService);
        $parametersBinder = new ParametersBinder($this->formFactory, LoginDTOType::class);
        $validator = new Validator($this->sfValidator);
        return new PostLoginAction($loginCommand, $parametersBinder, $validator);
    }
}
