<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\GetOilChangeWarningConfigurationsActionFactory;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\PatchOilChangeWarningConfigurationsActionFactory;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\OilChangeWarningObserver;
use Symfony\Component\HttpFoundation\Request;

class OilChangeWarningConfigurationsController extends MaintenanceWarningConfigurationController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warning-configurations/oil-changes")
     */
    public function getAction($motorcycleId)
    {
        return parent::getAction($motorcycleId);
    }
    
    /**
     * @Patch("/motorcycles/{motorcycleId}/warning-configurations/oil-changes")
     */
    public function patchAction($motorcycleId, Request $request)
    {
        return parent::patchAction($motorcycleId, $request);
    }

    protected function createGetAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factory = new GetOilChangeWarningConfigurationsActionFactory($em);
        return $factory->createGetAction(OilChange::class, OilChangeWarningObserver::class);
    }

    protected function createPatchAction()
    {
        $em = $this->getDoctrine()->getManager();
        $formFactory = $this->get('form.factory');
        $sfValidator = $this->get('validator');
        $actionFactory = new PatchOilChangeWarningConfigurationsActionFactory($formFactory, $sfValidator, $em);
        return $actionFactory->createPatchAction(OilChange::class, OilChangeWarningObserver::class);
    }
}
