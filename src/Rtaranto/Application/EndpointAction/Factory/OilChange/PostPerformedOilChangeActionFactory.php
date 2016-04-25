<?php
namespace Rtaranto\Application\EndpointAction\Factory\OilChange;

use Doctrine\ORM\EntityManagerInterface;
use Rtaranto\Application\EndpointAction\Factory\PostActionFactoryInterface;
use Rtaranto\Application\EndpointAction\PerformedMaintenance\PostPerformedOilChangeAction;
use Rtaranto\Application\EndpointAction\RequestParamsProcessor;
use Rtaranto\Application\ParametersBinder\ParametersBinder;
use Rtaranto\Application\Service\Maintenance\OilChangerService;
use Rtaranto\Application\Service\Validator\PerformedMaintenanceDTOValidator;
use Rtaranto\Application\Service\Validator\Validator;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Infrastructure\Repository\DoctrineMaintenanceRepository;
use Rtaranto\Presentation\Form\Maintenance\PerformedMaintenanceDTOType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostPerformedOilChangeActionFactory implements PostActionFactoryInterface
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
        $performedMaintenanceDTOValidator = new PerformedMaintenanceDTOValidator($this->sfValidator);
        $inputProcessor = new RequestParamsProcessor($parametersBinder, $performedMaintenanceDTOValidator);
        
        $oilChangeRepository = new DoctrineMaintenanceRepository($this->em, OilChange::class);
        $validator = new Validator($this->sfValidator);
        $oilChangerService = new OilChangerService($oilChangeRepository, $validator);
        
        return new PostPerformedOilChangeAction($inputProcessor, $oilChangerService);
    }

}
