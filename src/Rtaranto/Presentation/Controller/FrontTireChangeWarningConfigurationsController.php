<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\GetFrontTireChangeWarningConfigurationsActionFactory;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\PatchFrontTireChangeWarningConfigurationsActionFactory;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\FrontTireChangeWarningObserver;
use Symfony\Component\HttpFoundation\Request;

class FrontTireChangeWarningConfigurationsController extends MaintenanceWarningConfigurationController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warning-configurations/front-tire-changes")
     */
    public function getAction($motorcycleId)
    {
        return parent::getAction($motorcycleId);
    }

    /**
     * @Patch("/motorcycles/{motorcycleId}/warning-configurations/front-tire-changes")
     */
    public function patchAction($motorcycleId, Request $request)
    {
        return parent::patchAction($motorcycleId, $request);
    }
    
    protected function getMaintenanceClassName()
    {
        return FrontTireChange::class;
    }
    protected function getMaintenanceWarningObserverClassName()
    {
        return FrontTireChangeWarningObserver::class;
    }

    protected function createGetAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factory = new GetFrontTireChangeWarningConfigurationsActionFactory($em);
        return $factory->createGetAction(FrontTireChange::class, FrontTireChangeWarningObserver::class);
    }
    
    protected function createPatchAction()
    {
        $em = $this->getDoctrine()->getManager();
        $formFactory = $this->get('form.factory');
        $sfValidator = $this->get('validator');
        $actionFactory = new PatchFrontTireChangeWarningConfigurationsActionFactory($formFactory, $sfValidator, $em);
        return $actionFactory->createPatchAction(FrontTireChange::class, FrontTireChangeWarningObserver::class);
    }
}
