<?php
namespace Rtaranto\Presentation\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use Rtaranto\Application\EndpointAction\Factory\WarningsConfiguration\GetWarningsConfigurationActionFactory;
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
        return array(
            'is_active' => true,
            'kms_per_oil_change' => 1500,
            'kms_in_advance' => 100
        );
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
