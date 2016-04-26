<?php
namespace Rtaranto\Presentation\Controller;

use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\GetWarningsConfigurationActionFactory;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\PatchWarningConfigurationActionFactory;
use Rtaranto\Application\EndpointAction\WarningsConfiguration\GetWarningsConfigurationAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class MaintenanceWarningConfigurationController extends MotorcycleSubResourceController
{
    abstract protected function getMaintenanceClassName();
    abstract protected function getMaintenanceWarningObserverClassName();
    
    public function getAction($motorcycleId)
    {
        $this->throwExceptionIfNotBiker();
        $this->throwNotFoundIfMotorcycleDoesntBelongsToBiker($motorcycleId);
        
        $getAction = $this->createGetAction();
        return $getAction->get($motorcycleId);
    }
    
    public function patchAction($motorcycleId, Request $request)
    {
        $patchAction = $this->createPatchAction();
        try {
            $maintenanceWarnignConfigurationDTO = $patchAction->patch($motorcycleId, $request->request->all());
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
    protected function createGetAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factory = new GetWarningsConfigurationActionFactory($em);
        $maintenanceClassName = $this->getMaintenanceClassName();
        
        $maintenanceWarningObserverClassName = $this->getMaintenanceWarningObserverClassName();
        return $factory->createGetAction($maintenanceClassName, $maintenanceWarningObserverClassName);
    }
    
    protected function createPatchAction()
    {
        $em = $this->getDoctrine()->getManager();
        $formFactory = $this->get('form.factory');
        $sfValidator = $this->get('validator');
        $actionFactory = new PatchWarningConfigurationActionFactory($formFactory, $sfValidator, $em);
        $maintenanceClassName = $this->getMaintenanceClassName();
        $maintenanceWarningObserverClassName = $this->getMaintenanceWarningObserverClassName();
        return $actionFactory->createPatchAction($maintenanceClassName, $maintenanceWarningObserverClassName);
    }
}
