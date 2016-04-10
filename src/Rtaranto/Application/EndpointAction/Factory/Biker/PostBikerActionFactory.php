<?php
namespace Rtaranto\Application\EndpointAction\Factory\Biker;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Biker\PostBikerAction;
use Rtaranto\Application\EndpointAction\Factory\PostActionFactoryInterface;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Presentation\Form\Biker\BikerDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostBikerActionFactory implements PostActionFactoryInterface
{
    private $formFactory;
    private $symfonyValidator;
    private $em;
    
    public function __construct(
        FormFactoryInterface $formFactory,
        ValidatorInterface $symfonyValidator,
        EntityManagerInterface $em
    ) {
        $this->formFactory = $formFactory;
        $this->symfonyValidator = $symfonyValidator;
        $this->em = $em;
    }
    
    public function createPostAction()
    {
        $parametersBinder = new ParametersBinder($this->formFactory, BikerDTOType::class);
        $validator = new Validator($this->symfonyValidator);
        $doctrineBikerRepository = new DoctrineBikerRepository($this->em);
        return new PostBikerAction($parametersBinder, $validator, $doctrineBikerRepository);
    }
}
