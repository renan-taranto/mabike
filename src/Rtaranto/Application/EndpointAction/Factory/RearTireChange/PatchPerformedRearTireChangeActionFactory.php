<?php
namespace Rtaranto\Application\EndpointAction\Factory\RearTireChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\PatchActionFactoryInterface;
use Rtaranto\Application\EndpointAction\RearTireChange\PatchPerfomedRearTireChangeAction;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Maintenance\TireChange\PerformedRearTireChangePatcher;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\PerformedRearTireChange;
use Rtaranto\Infrastructure\Repository\DoctrineSubResourceRepository;
use Rtaranto\Presentation\Form\Maintenance\PerformedMaintenanceDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatchPerformedRearTireChangeActionFactory implements PatchActionFactoryInterface
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
    
    public function createPatchAction()
    {
        $parametersBinder = new ParametersBinder($this->formFactory, PerformedMaintenanceDTOType::class);
        $validator = new Validator($this->sfValidator);
        $inputProcessor = new RequestParamsProcessor($parametersBinder, $validator);
        $subResourceRepository = new DoctrineSubResourceRepository($this->em, 'motorcycle', PerformedRearTireChange::class);
        $performedRearTireChangePatcher = new PerformedRearTireChangePatcher($subResourceRepository, $validator);
        return new PatchPerfomedRearTireChangeAction(
            $subResourceRepository,
            $inputProcessor,
            $performedRearTireChangePatcher);
    }
}
