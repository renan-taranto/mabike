<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\GetWarningsConfigurationActionFactory;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\PatchWarningConfigurationActionFactory;
use Rtaranto\Application\EndpointAction\WarningsConfiguration\GetWarningsConfigurationAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        
        try {
            $maintenanceWarnignConfigurationDTO = $action->patch($motorcycleId, $request->request->all());
        }
        catch (ValidationFailedException $ex) {
            $view = $this->view($ex->getErrors(), Response::HTTP_BAD_REQUEST);
            return $view;
        }
        
        return $maintenanceWarnignConfigurationDTO;
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
