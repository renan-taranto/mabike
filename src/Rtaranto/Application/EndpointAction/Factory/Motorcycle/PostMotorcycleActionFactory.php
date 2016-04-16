<?php
namespace Rtaranto\Application\EndpointAction\Factory\Motorcycle;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\PostActionFactoryInterface;
use Rtaranto\Application\EndpointAction\Motorcycle\PostMotorcycleAction;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\User;
use Rtaranto\Infrastructure\Repository\DoctrineBikerRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Rtaranto\Presentation\Form\Motorcycle\MotorcycleDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostMotorcycleActionFactory implements PostActionFactoryInterface
{
    private $em;
    private $formFactory;
    private $sfValidator;
    private $user;
    
    public function __construct(
        User $user,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        ValidatorInterface $sfValidator
    ) {
        $this->user = $user;
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->sfValidator = $sfValidator;
    }
    
    /**
     * @return PostMotorcycleAction
     */
    public function createPostAction()
    {
        $bikerRepository = new DoctrineBikerRepository($this->em);
        $biker = $bikerRepository->findOneByUser($this->user);
        $motorcycleRepository = new DoctrineMotorcycleRepository($this->em, $bikerRepository);
        $parametersBinder = new ParametersBinder($this->formFactory, MotorcycleDTOType::class);
        $validator = new Validator($this->sfValidator);
        return new PostMotorcycleAction($biker, $motorcycleRepository, $parametersBinder, $validator);
    }
}
