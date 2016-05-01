<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\GetFrontTireChangeWarningConfigurationsActionFactory;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\PatchFrontTireChangeWarningConfigurationsActionFactory;
use Rtaranto\Domain\Entity\FrontTireChange;
use Rtaranto\Domain\Entity\FrontTireChangeWarningObserver;
use Symfony\Component\HttpFoundation\Request;

class FrontTireChangeWarningConfigurationsController extends MaintenanceWarningConfigurationController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warning-configurations/front-tire-changes")
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a Front Tire Changes Warning Configurations",
     *  requirements={
     *      {"name"="motorcycleId", "dataType"="integer", "required"=true, "description"="Motorcycle id"},
     *  }
     * )
     */
    public function getAction($motorcycleId)
    {
        return parent::getAction($motorcycleId);
    }

    /**
     * @Patch("/motorcycles/{motorcycleId}/warning-configurations/front-tire-changes")
     * @ApiDoc(
     *  description="Updates a Front Tire Changes Warning Configurations",
     *  requirements={
     *      {"name"="motorcycleId", "dataType"="integer", "required"=true, "description"="Motorcycle id"},
     *  },
     *  parameters={
     *      {"name"="is_active", "dataType"="boolean", "required"=false, "description"="Turn warnings on or off."},
     *      {"name"="kms_per_maintenance", "dataType"="integer", "required"=false, "description"="Kms per maintenance performing."},
     *      {"name"="kms_in_advance", "dataType"="integer", "required"=false, "description"="Number of kms to trigger warning before next maintenance to be performed."},
     *  }
     * )
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
