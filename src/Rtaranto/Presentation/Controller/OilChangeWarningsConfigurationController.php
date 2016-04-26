<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\GetWarningsConfigurationActionFactory;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\PatchWarningConfigurationActionFactory;
use Rtaranto\Application\EndpointAction\WarningsConfiguration\GetWarningsConfigurationAction;
use Symfony\Component\HttpFoundation\Request;

class OilChangeWarningsConfigurationController extends BikerSubResourceController
{
    /**
     * @Get("/motorcycles/{motorcycleId}/warnings-configuration/oil-change")
     */
    public function getAction($motorcycleId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $getAction = $this->createGetAction();
        return $getAction->get($motorcycleId);
    }
    
    /**
     * @Patch("/motorcycles/{motorcycleId}/warnings-configuration/oil-change")
     */
    public function patchAction($motorcycleId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $formFactory = $this->get('form.factory');
        $sfValidator = $this->get('validator');
        $actionFactory = new PatchWarningConfigurationActionFactory($formFactory, $sfValidator, $em);
        $action = $actionFactory->createPatchAction();
        return $action->patch($motorcycleId, $request->request->all());
    }

    /**
     * @return GetWarningsConfigurationAction
     */
    private function createGetAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factory = new GetWarningsConfigurationActionFactory($em);
        return $factory->createGetAction();
    }
}
