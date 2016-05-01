<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\GetOilChangeWarningConfigurationsActionFactory;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\PatchOilChangeWarningConfigurationsActionFactory;
use Rtaranto\Domain\Entity\OilChange;
use Rtaranto\Domain\Entity\OilChangeWarningObserver;
use Symfony\Component\HttpFoundation\Request;

class OilChangeWarningConfigurationsController extends MaintenanceWarningConfigurationController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warning-configurations/oil-changes")
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a Oil Changes Warning Configurations",
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
     * @Patch("/motorcycles/{motorcycleId}/warning-configurations/oil-changes")
     * @ApiDoc(
     *  description="Updates a Oil Changes Warning Configurations",
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
