<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\GetRearTireChangeWarningConfigurationsActionFactory;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\PatchRearTireChangeWarningConfigurationsActionFactory;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Domain\Entity\RearTireChangeWarningObserver;
use Symfony\Component\HttpFoundation\Request;

class RearTireChangeWarningConfigurationsController extends MaintenanceWarningConfigurationController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warning-configurations/rear-tire-changes")
     */
    public function getAction($motorcycleId)
    {
        return parent::getAction($motorcycleId);
    }

    /**
     * @Patch("/motorcycles/{motorcycleId}/warning-configurations/rear-tire-changes")
     */
    public function patchAction($motorcycleId, Request $request)
    {
        return parent::patchAction($motorcycleId, $request);
    }

    protected function createGetAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factory = new GetRearTireChangeWarningConfigurationsActionFactory($em);
        return $factory->createGetAction(RearTireChange::class, RearTireChangeWarningObserver::class);
    }
    
    protected function createPatchAction()
    {
        $em = $this->getDoctrine()->getManager();
        $formFactory = $this->get('form.factory');
        $sfValidator = $this->get('validator');
        $actionFactory = new PatchRearTireChangeWarningConfigurationsActionFactory($formFactory, $sfValidator, $em);
        return $actionFactory->createPatchAction(RearTireChange::class, RearTireChangeWarningObserver::class);
    }
}
