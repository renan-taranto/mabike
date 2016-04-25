<?php
namespace Rtaranto\Application\EndpointAction\Factory\RearTireChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\PostActionFactoryInterface;
use Rtaranto\Application\EndpointAction\PerformedMaintenance\PostPerformedMaintenanceAction;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\PerformedMaintenance\RearTireChangerService;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Rtaranto\Infrastructure\Repository\DoctrineMotorcycleRepository;
use Rtaranto\Presentation\Form\Maintenance\PerformedMaintenanceDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostPerformedRearTireChangeActionFactory implements PostActionFactoryInterface
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
        $parametersBinder = new ParametersBinder($this->formFactory, PerformedMaintenanceDTOType::class);
        $validator = new Validator($this->sfValidator);
        $inputProcessor = new RequestParamsProcessor($parametersBinder, $validator);
        
        $rearTireChangeRepository = new DoctrineMaintenanceRepository($this->em, RearTireChange::class);
        $motorcycleRepository = new DoctrineMotorcycleRepository($this->em);
        $rearTireChangePerformerService = new RearTireChangerService(
            $rearTireChangeRepository,
            $validator,
            $motorcycleRepository
        );
        return new PostPerformedMaintenanceAction($inputProcessor, $rearTireChangePerformerService);
    }
}
