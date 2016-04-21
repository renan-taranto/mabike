<?php
namespace Rtaranto\Application\EndpointAction\Factory\FrontTireChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\PostActionFactoryInterface;
use Rtaranto\Application\EndpointAction\FrontTireChange\PostPerformedFrontTireChangeAction;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Maintenance\TireChange\FrontTireChangerService;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Infrastructure\Repository\DoctrineFrontTireChangeRepository;
use Rtaranto\Presentation\Form\Maintenance\PerformedMaintenanceDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostPerformedFrontTireChangeActionFactory implements PostActionFactoryInterface
{
    private $formFactory;
    private $sfValidator;
    private $em;
    
    public function __construct(
        FormFactoryInterface $formFactory,
        ValidatorInterface $sfValidator,
        EntityManagerInterface $em)
    {
        $this->formFactory = $formFactory;
        $this->sfValidator = $sfValidator;
        $this->em = $em;
    }
    
    public function createPostAction()
    {
        $parametersBinder = new ParametersBinder($this->formFactory, PerformedMaintenanceDTOType::class);
        $validator = new Validator($this->sfValidator);
        $requestParamsProcessor = new RequestParamsProcessor($parametersBinder, $validator);
        $frontTireChangeRepository = new DoctrineFrontTireChangeRepository($this->em);
        $frontTireChangerService = new FrontTireChangerService($frontTireChangeRepository, $validator);
        return new PostPerformedFrontTireChangeAction($requestParamsProcessor, $frontTireChangerService);
    }

}
