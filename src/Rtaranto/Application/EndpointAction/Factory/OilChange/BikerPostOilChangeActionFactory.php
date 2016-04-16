<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Rtaranto\Application\EndpointAction\Factory\PostActionFactoryInterface;
use Rtaranto\Application\EndpointAction\OilChange\BikerPostOilChangeAction;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\OilChangePerformer;
use Rtaranto\Infrastructure\Repository\DoctrineOilChangePerformerRepository;
use Rtaranto\Presentation\Form\Maintenance\MaintenanceDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BikerPostOilChangeActionFactory implements PostActionFactoryInterface
{
    private $formFactory;
    private $sfValidator;
    private $em;
    
    public function __construct(
        FormFactoryInterface $formFactory,
        ValidatorInterface $sfValidator,
        EntityManagerInterface $em
    ) {
        $this->formFactory = $formFactory;
        $this->sfValidator = $sfValidator;
        $this->em = $em;
    }

    public function createPostAction()
    {
        $parametersBinder = new ParametersBinder($this->formFactory, MaintenanceDTOType::class);
        $validator = new Validator($this->sfValidator);
        $classMetadata = new ClassMetadata(OilChangePerformer::class);
        $oilChangePerformerRepository = new DoctrineOilChangePerformerRepository($this->em, $classMetadata);
        return new BikerPostOilChangeAction(
            $parametersBinder,
            $validator,
            $oilChangePerformerRepository
        );
    }

}
