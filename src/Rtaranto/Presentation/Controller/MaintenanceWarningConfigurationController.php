<?php
namespace Rtaranto\Presentation\Controller;

use Rtaranto\Application\EndpointAction\WarningsConfiguration\GetWarningsConfigurationAction;
use Rtaranto\Application\Exception\ValidationFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class MaintenanceWarningConfigurationController extends MotorcycleSubResourceController
{
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
    abstract protected function createGetAction();
    
    abstract protected function createPatchAction();
}
