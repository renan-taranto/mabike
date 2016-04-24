<?php
namespace Rtaranto\Presentation\Controller;

use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\GetWarningsConfigurationActionFactory;
use Rtaranto\Application\EndpointAction\WarningsConfiguration\GetWarningsConfigurationAction;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;

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
     * @return GetWarningsConfigurationAction
     */
    private function createGetAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factory = new GetWarningsConfigurationActionFactory($em);
        return $factory->createGetAction();
    }
}
