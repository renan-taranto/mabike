<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\PatchActionFactoryInterface;
use Rtaranto\Application\EndpointAction\OilChange\PatchPerformedOilChangeAction;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Maintenance\OilChange\PerformedOilChangePatcher;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Infrastructure\Repository\DoctrinePerformedOilChangeRepository;
use Rtaranto\Presentation\Form\Maintenance\PerformedMaintenanceDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatchPerformedOilChangeActionFactory implements PatchActionFactoryInterface
{
    private $em;
    private $sfValidator;
    private $formFactory;
    
    public function __construct(
        FormFactoryInterface $formFactory,
        ValidatorInterface $sfValidator,
        EntityManagerInterface $em
    ) {
        $this->formFactory = $formFactory;
        $this->sfValidator = $sfValidator;
        $this->em = $em;
    }
    
    public function createPatchAction()
    {
        $parametersBinder = new ParametersBinder($this->formFactory, PerformedMaintenanceDTOType::class);
        $validator = new Validator($this->sfValidator);
        $inputProcessor = new RequestParamsProcessor($parametersBinder, $validator);
        $performedOilChangeRepository = new DoctrinePerformedOilChangeRepository($this->em);
        $performedOilChangePatcher = new PerformedOilChangePatcher($performedOilChangeRepository, $validator);
        return new PatchPerformedOilChangeAction($performedOilChangeRepository, $inputProcessor, $performedOilChangePatcher);
    }
}
