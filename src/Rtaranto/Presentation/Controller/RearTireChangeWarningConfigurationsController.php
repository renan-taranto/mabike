<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\GetRearTireChangeWarningConfigurationsActionFactory;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\PatchRearTireChangeWarningConfigurationsActionFactory;
use Rtaranto\Domain\Entity\RearTireChange;
use Rtaranto\Domain\Entity\RearTireChangeWarningObserver;
use Symfony\Component\HttpFoundation\Request;

class RearTireChangeWarningConfigurationsController extends MaintenanceWarningConfigurationController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warning-configurations/rear-tire-changes")
     * @ApiDoc(
     *  resource=true,
     *  description="Returns a Rear Tire Changes Warning Configurations",
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
     * @Patch("/motorcycles/{motorcycleId}/warning-configurations/rear-tire-changes")
     * @ApiDoc(
     *  description="Updates a Rear Tire Changes Warning Configurations",
     *  requirements={
     *      {"name"="motorcycleId", "dataType"="integer", "required"=true, "description"="Motorcycle id"}
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
